<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\KycDocument;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // Web Methods
    public function dashboard()
    {
        $pendingMembersCount = User::pending()->count();
        $pendingKycCount = KycDocument::pending()->count();
        $totalMembersCount = User::whereIn('status', ['approved', 'kyc_verified'])->count();
        $totalWalletBalance = Wallet::sum('balance');
        $recentMembers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'pendingMembersCount',
            'pendingKycCount',
            'totalMembersCount',
            'totalWalletBalance',
            'recentMembers'
        ));
    }

    public function membersIndex(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $kycStatus = $request->get('kyc_status');
        $vipStatus = $request->get('vip_status');
        
        $members = User::where('role', 'user')
            ->when($search, function ($query) use ($search) {
                return $query->search($search);
            })
            ->when($status, function ($query) use ($status) {
                return $query->filterByStatus($status);
            })
            ->when($kycStatus, function ($query) use ($kycStatus) {
                return $query->filterByKycStatus($kycStatus);
            })
            ->when($vipStatus, function ($query) use ($vipStatus) {
                return $query->filterByVipStatus($vipStatus);
            })
            ->with(['wallet', 'kycDocuments'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => User::where('role', 'user')->count(),
            'pending' => User::pending()->count(),
            'approved' => User::approved()->count(),
            'kyc_verified' => User::kycVerified()->count(),
            'vip' => User::vip()->count(),
            'no_kyc' => User::noKycSubmitted()->count(),
        ];

        return view('admin.members.index', compact('members', 'stats', 'search', 'status', 'kycStatus', 'vipStatus'));
    }

    public function viewMember(User $user) {
        return view('admin.members.view', [
            'user' => $user
        ]);
    }

    public function updateMember(Request $request, User $user) {
        $validated = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'address'           => 'string|max:500',
            'phone_number'      => 'string|max:500',
            'email'             => 'required|email|unique:users,email,' . $user->id,
            'status'            => 'required',
        ]);

        $user->update($validated);

        if ($request->input('is_vip')) {
            $user->markAsVip();
        } else {
            $user->removeVip();
        }

        return back()->with('success', 'User updated successfully.');
    }

    public function toggleVip(User $user)
    {
        if ($user->isVip()) {
            $user->removeVip();
            $message = 'VIP status removed successfully.';
        } else {
            $user->markAsVip();
            $message = 'User marked as VIP successfully.';
        }

        if (request()->expectsJson()) {
            return response()->json(['message' => $message, 'is_vip' => $user->fresh()->is_vip]);
        }

        return back()->with('success', $message);
    }

    public function pendingMembers()
    {
        $members = User::pending()->with(['kycDocuments'])->get();
        
        return view('admin.members.pending', compact('members'));
    }

    public function pendingKyc()
    {
        $documents = KycDocument::pending()->with(['user'])->get();
        
        return view('admin.kyc.pending', compact('documents'));
    }

    public function wallets()
    {
        $wallets = Wallet::with(['user', 'transactions'])->get();
        
        return view('admin.wallets.index', compact('wallets'));
    }

    // API Methods for Web Forms
    public function approveMember(User $user)
    {

        // Generate member ID
        $user->approveMember();
        // Create wallet
        Wallet::create(['user_id' => $user->id, 'balance' => 0]);

        return back()->with('success', 'Member approved successfully. Member ID: ' . $memberId);
    }

    public function rejectMember(Request $request, User $user)
    {
        $user->update(['status' => -1]);

        return back()->with('success', 'Member rejected successfully.');
    }

    public function approveKyc(Request $request, KycDocument $kyc)
    {
        $kyc->update(['status' => 'approved']);

        // Check if user has any approved KYC documents
        $user = $kyc->user;
        if ($user->kycDocuments()->approved()->exists()) {
            $user->update(['status' => 2]);
        }

        return back()->with('success', 'KYC document approved successfully.');
    }

    public function rejectKyc(Request $request, KycDocument $kyc)
    {
        $request->validate([
            'remarks' => 'required|string|max:1000',
        ]);

        $kyc->update([
            'status' => 'rejected',
            'remarks' => $request->remarks,
        ]);

        return back()->with('success', 'KYC document rejected successfully.');
    }

    public function addCredit(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:10000',
            'description' => 'nullable|string|max:255',
        ]);

        if (!$user->wallet) {
            return back()->with('error', 'User wallet not found.');
        }

        $transaction = $user->wallet->addCredit(
            $request->amount,
            $request->description ?? 'Manual credit by admin'
        );

        return back()->with('success', 'Credit added successfully. New balance: $' . number_format($user->wallet->fresh()->balance, 2));
    }
}