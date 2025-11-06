<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'balance'];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function addCredit(float $amount, string $description = null): Transaction
    {
        $this->balance += $amount;
        $this->save();

        return $this->transactions()->create([
            'amount' => $amount,
            'type' => 'add',
            'description' => $description ?? 'Credit added',
        ]);
    }

    public function spendCredit(float $amount, string $description = null): ?Transaction
    {
        if ($this->balance < $amount) {
            return null;
        }

        $this->balance -= $amount;
        $this->save();

        return $this->transactions()->create([
            'amount' => $amount,
            'type' => 'spend',
            'description' => $description ?? 'Credit spent',
        ]);
    }
}