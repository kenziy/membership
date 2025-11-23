<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'status',
        'phone_number',
        'invited_by',
        'address',
        'member_id',
        'profile_photo_path',
        'qr_code_path',
        'role',
        'is_vip',
        'vip_since',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_vip' => 'boolean',
        'vip_since' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
        'role' => 'user',
        'is_vip' => false,
    ];

    // Relationships
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function kycDocuments()
    {
        return $this->hasMany(KycDocument::class);
    }

    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Wallet::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeKycVerified($query)
    {
        return $query->where('status', 'kyc_verified');
    }

    public function scopeKycPending($query)
    {
        return $query->where('status', 'kyc_pending');
    }

    public function scopeNoKycSubmitted($query)
    {
        return $query->whereDoesntHave('kycDocuments');
    }

    public function scopePendingKycReview($query)
    {
        return $query->whereHas('kycDocuments', function ($q) {
            $q->where('status', 'pending');
        });
    }

    public function scopeVip($query)
    {
        return $query->where('is_vip', true);
    }

    public function scopeRegular($query)
    {
        return $query->where('is_vip', false);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('member_id', 'like', "%{$search}%");
        });
    }

    public function scopeFilterByStatus($query, $status)
    {
        if ($status === 'pending') {
            return $query->pending();
        } elseif ($status === 'approved') {
            return $query->approved();
        } elseif ($status === 'kyc_verified') {
            return $query->kycVerified();
        } elseif ($status === 'kyc_pending') {
            return $query->kycPending();
        }

        return $query;
    }

    public function scopeFilterByKycStatus($query, $kycStatus)
    {
        if ($kycStatus === 'no_kyc') {
            return $query->noKycSubmitted();
        } elseif ($kycStatus === 'pending_review') {
            return $query->pendingKycReview();
        } elseif ($kycStatus === 'has_kyc') {
            return $query->whereHas('kycDocuments');
        }

        return $query;
    }

    public function scopeFilterByVipStatus($query, $vipStatus)
    {
        if ($vipStatus === 'vip') {
            return $query->vip();
        } elseif ($vipStatus === 'regular') {
            return $query->regular();
        }

        return $query;
    }

    // Methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isApproved(): bool
    {
        return $this->status === 1;
    }

    public function isKycVerified(): bool
    {
        return $this->status === 2;
    }

    public function status() {
        $status = '<span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                     bg-red-100 text-red-800 ">Rejected</span>';
        switch($this->status) {
            case 0:
                $status = '<span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                     bg-gray-100 text-gray-800 ">Pending</span>';
            break;
            case 1:
                $status = '<span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                     bg-orange-100 text-orange-800 ">Approved</span>';
            break;
            case 2:
                $status = '<span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                     bg-green-100 text-green-800 ">Verified</span>';
            break;
        }
        return $status;
    }

    public function hasPendingKyc(): bool
    {
        return $this->kycDocuments()->where('status', 'pending')->exists();
    }

    public function isVip(): bool
    {
        return $this->is_vip === true;
    }

    public function markAsVip(): void
    {
        $this->update([
            'is_vip' => true,
            'vip_since' => now(),
        ]);
    }

    public function removeVip(): void
    {
        $this->update([
            'is_vip' => false,
            'vip_since' => null,
        ]);
    }

    public function getKycStatusAttribute(): string
    {
        if ($this->isKycVerified()) {
            return 'verified';
        } elseif ($this->hasPendingKyc()) {
            return 'pending_review';
        } elseif ($this->kycDocuments()->exists()) {
            return 'submitted';
        }

        return 'not_submitted';
    }

    public function getKycStatusBadgeColorAttribute(): string
    {
        return match($this->kyc_status) {
            'verified' => 'green',
            'pending_review' => 'yellow',
            'submitted' => 'blue',
            default => 'gray'
        };
    }
}