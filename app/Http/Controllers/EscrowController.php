<?php

namespace App\Http\Controllers;

use App\Models\EscrowTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EscrowController extends Controller
{
    /**
     * Display escrow overview.
     */
    public function index()
    {
        $user = Auth::user();
        
        $escrows = EscrowTransaction::with(['task', 'service', 'growthListing', 'digitalProduct'])
            ->where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhere('seller_id', $user->id);
            })
            ->latest()
            ->paginate(15);

        // Calculate totals
        $totalInEscrow = EscrowTransaction::where('buyer_id', $user->id)
            ->where('status', 'held')
            ->sum('amount');
            
        $totalReleased = EscrowTransaction::where('seller_id', $user->id)
            ->where('status', 'released')
            ->sum('amount');

        return view('escrow.index', compact('escrows', 'totalInEscrow', 'totalReleased'));
    }

    /**
     * Display active escrows.
     */
    public function active()
    {
        $user = Auth::user();
        
        $escrows = EscrowTransaction::with(['task', 'service', 'growthListing', 'digitalProduct'])
            ->where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhere('seller_id', $user->id);
            })
            ->where('status', 'held')
            ->latest()
            ->paginate(15);

        return view('escrow.active', compact('escrows'));
    }

    /**
     * Display released escrows.
     */
    public function released()
    {
        $user = Auth::user();
        
        $escrows = EscrowTransaction::with(['task', 'service', 'growthListing', 'digitalProduct'])
            ->where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhere('seller_id', $user->id);
            })
            ->where('status', 'released')
            ->latest()
            ->paginate(15);

        return view('escrow.released', compact('escrows'));
    }

    /**
     * Display disputed escrows.
     */
    public function disputed()
    {
        $user = Auth::user();
        
        $escrows = EscrowTransaction::with(['task', 'service', 'growthListing', 'digitalProduct'])
            ->where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhere('seller_id', $user->id);
            })
            ->where('status', 'disputed')
            ->latest()
            ->paginate(15);

        return view('escrow.disputed', compact('escrows'));
    }

    /**
     * Release escrow.
     */
    public function release(EscrowTransaction $escrow)
    {
        $user = Auth::user();
        
        // Only buyer can release
        if ($escrow->buyer_id !== $user->id) {
            abort(403);
        }

        if ($escrow->status !== 'held') {
            return back()->with('error', 'This escrow cannot be released.');
        }

        $escrow->status = 'released';
        $escrow->released_at = now();
        $escrow->save();

        // Update the seller's wallet
        $seller = $escrow->seller;
        $seller->wallet->withdrawable_balance += $escrow->amount;
        $seller->wallet->save();

        return back()->with('success', 'Payment released successfully!');
    }

    /**
     * Raise dispute.
     */
    public function dispute(Request $request, EscrowTransaction $escrow)
    {
        $user = Auth::user();
        
        // Must be buyer or seller
        if ($escrow->buyer_id !== $user->id && $escrow->seller_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'reason' => 'required|string|min:20',
        ]);

        $escrow->status = 'disputed';
        $escrow->dispute_reason = $request->reason;
        $escrow->disputed_at = now();
        $escrow->disputer_id = $user->id;
        $escrow->save();

        return back()->with('success', 'Dispute raised. Our team will review and mediate.');
    }
}
