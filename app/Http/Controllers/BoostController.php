<?php

namespace App\Http\Controllers;

use App\Models\BoostPackage;
use App\Models\UserBoost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoostController extends Controller
{
    /**
     * Display boost packages and user's active boosts.
     */
    public function index()
    {
        $user = Auth::user();
        
        $packages = BoostPackage::where('is_active', true)->orderBy('price')->get();
        $activeBoosts = $user->boosts()
            ->where('expires_at', '>', now())
            ->with('package')
            ->get();

        return view('boost.index', compact('packages', 'activeBoosts'));
    }

    /**
     * Purchase and activate a boost package.
     */
    public function activate(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:boost_packages,id',
            'target_type' => 'required|in:task,service,product,job',
            'target_id' => 'required|integer',
        ]);

        $user = Auth::user();
        $package = BoostPackage::findOrFail($request->package_id);

        // Check if user has sufficient balance
        $totalBalance = $user->wallet->withdrawable_balance + $user->wallet->promo_credit_balance;
        if ($totalBalance < $package->price) {
            return back()->with('error', 'Insufficient balance. Please deposit funds to continue.');
        }

        // Deduct from wallet
        if ($user->wallet->withdrawable_balance >= $package->price) {
            $user->wallet->withdrawable_balance -= $package->price;
        } else {
            $remaining = $package->price - $user->wallet->withdrawable_balance;
            $user->wallet->withdrawable_balance = 0;
            $user->wallet->promo_credit_balance -= $remaining;
        }
        $user->wallet->save();

        // Create boost record
        $boost = new UserBoost([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'target_type' => $request->target_type,
            'target_id' => $request->target_id,
            'started_at' => now(),
            'expires_at' => now()->addDays($package->duration_days),
            'status' => 'active',
        ]);
        $boost->save();

        return back()->with('success', 'Boost activated successfully!');
    }

    /**
     * Extend boost duration.
     */
    public function extend(Request $request, UserBoost $boost)
    {
        $request->validate([
            'package_id' => 'required|exists:boost_packages,id',
        ]);

        $user = Auth::user();
        $package = BoostPackage::findOrFail($request->package_id);

        if ($boost->user_id !== $user->id) {
            abort(403);
        }

        // Check balance and deduct
        $totalBalance = $user->wallet->withdrawable_balance + $user->wallet->promo_credit_balance;
        if ($totalBalance < $package->price) {
            return back()->with('error', 'Insufficient balance.');
        }

        // Extend expiration
        $boost->expires_at = $boost->expires_at->addDays($package->duration_days);
        $boost->save();

        // Deduct from wallet
        if ($user->wallet->withdrawable_balance >= $package->price) {
            $user->wallet->withdrawable_balance -= $package->price;
        } else {
            $remaining = $package->price - $user->wallet->withdrawable_balance;
            $user->wallet->withdrawable_balance = 0;
            $user->wallet->promo_credit_balance -= $remaining;
        }
        $user->wallet->save();

        return back()->with('success', 'Boost extended successfully!');
    }

    /**
     * Cancel an active boost.
     */
    public function cancel(UserBoost $boost)
    {
        $user = Auth::auth();

        if ($boost->user_id !== $user->id) {
            abort(403);
        }

        if ($boost->expires_at <= now()) {
            return back()->with('error', 'This boost has already expired.');
        }

        // Calculate refund (50% of remaining days)
        $remainingDays = now()->diffInDays($boost->expires_at);
        $totalDays = $boost->started_at->diffInDays($boost->expires_at);
        
        if ($remainingDays > 0 && $totalDays > 0) {
            $refundPercentage = ($remainingDays / $totalDays) * 0.5;
            $package = $boost->package;
            $refundAmount = $package->price * $refundPercentage;
            
            // Refund to wallet
            $user->wallet->withdrawable_balance += $refundAmount;
            $user->wallet->save();
        }

        $boost->status = 'cancelled';
        $boost->save();

        return back()->with('success', 'Boost cancelled. Partial refund has been credited to your wallet.');
    }
}
