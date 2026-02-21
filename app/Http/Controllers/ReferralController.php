<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\Transaction;
use App\Services\SwiftKudiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReferralController extends Controller
{
    public function index()
    {
        $referrals = Referral::where('user_id', Auth::id())
            ->with('referred')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_referrals' => Referral::where('user_id', Auth::id())->count(),
            'registered' => Referral::where('user_id', Auth::id())->where('is_registered', true)->count(),
            'activated' => Referral::where('user_id', Auth::id())->where('is_activated', true)->count(),
            'total_earned' => Referral::where('user_id', Auth::id())->sum('reward_earned'),
        ];

        // Get or create referral code
        $referralCode = Referral::where('user_id', Auth::id())->first();
        if (!$referralCode) {
            $referralCode = Referral::create([
                'user_id' => Auth::id(),
                'referral_code' => Referral::generateReferralCode(Auth::id()),
            ]);
        } else {
            // Ensure code exists
            if (!$referralCode->referral_code) {
                $referralCode->update(['referral_code' => Referral::generateReferralCode(Auth::id())]);
            }
        }

        return view('referrals.index', compact('referrals', 'stats', 'referralCode'));
    }

    public function registerWithCode(Request $request)
    {
        // This would be used during registration
        session(['referral_code' => $request->code]);
        return response()->json(['success' => true]);
    }

    public function redirectWithCode($code)
    {
        // Accept a short referral link like /ref/{code} and store in session then redirect to registration
        session(['referral_code' => $code]);
        return redirect()->route('register');
    }

    public function checkReferral(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $referral = Referral::where('referral_code', $request->code)->first();

        if (!$referral) {
            return response()->json(['success' => false, 'message' => 'Invalid referral code']);
        }

        if ($referral->user_id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Cannot refer yourself']);
        }

        return response()->json([
            'success' => true,
            'user' => $referral->user->name,
            'reward' => '₦' . number_format(SwiftKudiService::REFERRER_BONUS, 2),
        ]);
    }

    public function processReferralBonus($referralId)
    {
        $referral = Referral::findOrFail($referralId);

        if ($referral->is_activated || $referral->reward_earned > 0) {
            return back()->with('info', 'Bonus already processed');
        }

        DB::transaction(function () use ($referral) {
            // Use configured bonus from service constants
            $bonus = SwiftKudiService::REFERRER_BONUS;

            $referrerWallet = $referral->user->wallet;
            if (!$referrerWallet) {
                Log::warning('Attempted to credit referral bonus but referrer has no wallet', ['referral_id' => $referral->id]);
                return;
            }

            // Credit the configured bonus to referrer's withdrawable balance
            $referrerWallet->addWithdrawable($bonus, 'referral_bonus', 'Referral bonus for ' . ($referral->referredUser->name ?? 'new user'));

            // Record transaction
            Transaction::create([
                'wallet_id' => $referrerWallet->id,
                'user_id' => $referral->user_id,
                'type' => Transaction::TYPE_REFERRAL_BONUS,
                'amount' => $bonus,
                'currency' => 'NGN',
                'status' => 'completed',
                'description' => 'Referral bonus for ' . ($referral->referredUser->name ?? 'new user'),
                'reference' => Transaction::generateReference('REF'),
            ]);

            // Update referral
            $referral->update(['reward_earned' => $bonus, 'is_activated' => true]);

            Log::info('Referral bonus processed', ['referral_id' => $referral->id, 'referrer_id' => $referral->user_id, 'amount' => $bonus]);
        });

        return back()->with('success', 'Referral bonus processed!');
    }
}
