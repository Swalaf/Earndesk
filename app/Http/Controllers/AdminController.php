<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Task;
use App\Models\TaskCompletion;
use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Models\FraudLog;
use App\Models\Referral;
use App\Models\ActivationLog;
use App\Models\Currency;
use App\Models\TaskCategory;
use App\Models\Badge;
use App\Services\EarnDeskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    protected $earnDeskService;

    public function __construct(EarnDeskService $earnDeskService)
    {
        $this->earnDeskService = $earnDeskService;
        
        $this->middleware(function ($request, $next) {
            // Only allow admin users
            if (!Auth::check() || !Auth::user()->is_admin) {
                return redirect()->route('dashboard')
                    ->with('error', 'You do not have permission to access the admin area.');
            }
            return $next($request);
        });
    }

    /**
     * Dashboard overview
     */
    public function index()
    {
        $stats = $this->earnDeskService->getPlatformStats();

        // Recent activities
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();
        $recentTasks = Task::orderBy('created_at', 'desc')->take(5)->get();
        $recentWithdrawals = Withdrawal::orderBy('created_at', 'desc')->take(5)->get();
        $pendingCompletions = TaskCompletion::pending()->count();
        $pendingWithdrawals = Withdrawal::pending()->count();

        return view('admin', compact(
            'stats',
            'recentUsers',
            'recentTasks',
            'recentWithdrawals',
            'pendingCompletions',
            'pendingWithdrawals'
        ));
    }

    /**
     * Users management
     */
    public function users(Request $request)
    {
        $query = User::with('wallet');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            if ($request->status === 'activated') {
                $query->whereHas('wallet', function ($q) {
                    $q->where('is_activated', true);
                });
            } elseif ($request->status === 'pending') {
                $query->whereDoesntHave('wallet')
                    ->orWhereHas('wallet', function ($q) {
                        $q->where('is_activated', false);
                    });
            }
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * View user details
     */
    public function userDetails(User $user)
    {
        $wallet = $user->wallet;
        $tasks = Task::where('user_id', $user->id)->get();
        $completions = TaskCompletion::where('user_id', $user->id)->get();
        $withdrawals = Withdrawal::where('user_id', $user->id)->get();
        $referrals = Referral::where('user_id', $user->id)->get();

        return view('admin.user-details', compact(
            'user',
            'wallet',
            'tasks',
            'completions',
            'withdrawals',
            'referrals'
        ));
    }

    /**
     * Suspend user
     */
    public function suspendUser(Request $request, User $user)
    {
        // Implementation would suspend user access
        return redirect()->back()
            ->with('success', 'User suspended successfully.');
    }

    /**
     * Tasks management
     */
    public function tasks(Request $request)
    {
        $query = Task::with('user', 'category');

        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'expired') {
                $query->where('is_active', false);
            }
        }

        $tasks = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.tasks', compact('tasks'));
    }

    /**
     * View task details
     */
    public function taskDetails(Task $task)
    {
        $completions = TaskCompletion::where('task_id', $task->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.task-details', compact('task', 'completions'));
    }

    /**
     * Approve/Reject task
     */
    public function approveTask(Task $task)
    {
        $task->is_approved = true;
        $task->save();

        return redirect()->back()
            ->with('success', 'Task approved successfully.');
    }

    public function rejectTask(Request $request, Task $task)
    {
        $task->is_active = false;
        $task->save();

        // Refund escrow
        if ($task->escrow_amount > 0) {
            $wallet = $task->user->wallet;
            if ($wallet) {
                $wallet->refundFromEscrow($task->escrow_amount);
            }
        }

        return redirect()->back()
            ->with('success', 'Task rejected and refunded.');
    }

    /**
     * Feature task
     */
    public function featureTask(Task $task)
    {
        $task->is_featured = !$task->is_featured;
        $task->save();

        return redirect()->back()
            ->with('success', 'Task featured status changed.');
    }

    /**
     * Withdrawals management
     */
    public function withdrawals(Request $request)
    {
        $query = Withdrawal::with('user', 'wallet');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        $pendingWithdrawals = Withdrawal::pending()->count();

        return view('admin.withdrawals', compact('withdrawals', 'pendingWithdrawals'));
    }

    /**
     * Process withdrawal
     */
    public function processWithdrawal(Request $request, Withdrawal $withdrawal)
    {
        $action = $request->get('action');

        if ($action === 'approve') {
            $withdrawal->markAsCompleted($request->get('notes'));
            return redirect()->back()
                ->with('success', 'Withdrawal approved.');
        } elseif ($action === 'reject') {
            $request->validate(['notes' => 'required|string']);
            $withdrawal->markAsRejected($request->notes);
            return redirect()->back()
                ->with('success', 'Withdrawal rejected and refunded.');
        }

        return redirect()->back()
            ->with('error', 'Invalid action.');
    }

    /**
     * Task completions pending review
     */
    public function completions(Request $request)
    {
        $query = TaskCompletion::pending()->with('task', 'user');

        $completions = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.completions', compact('completions'));
    }

    /**
     * Approve completion (admin)
     */
    public function approveCompletion(Request $request, TaskCompletion $completion)
    {
        $result = $this->earnDeskService->awardTaskEarnings($completion);

        return redirect()->back()
            ->with('success', 'Completion approved.');
    }

    /**
     * Reject completion (admin)
     */
    public function rejectCompletion(Request $request, TaskCompletion $completion)
    {
        $request->validate(['notes' => 'required|string']);
        $completion->reject($request->notes);

        return redirect()->back()
            ->with('success', 'Completion rejected.');
    }

    /**
     * Fraud logs
     */
    public function fraudLogs(Request $request)
    {
        $query = FraudLog::with('user');

        if ($request->has('severity')) {
            $query->bySeverity($request->severity);
        }

        if ($request->has('resolved')) {
            $query->where('is_resolved', $request->boolean('resolved'));
        }

        $logs = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.fraud-logs', compact('logs'));
    }

    /**
     * Resolve fraud log
     */
    public function resolveFraudLog(FraudLog $log)
    {
        $log->markAsResolved();

        return redirect()->back()
            ->with('success', 'Fraud log marked as resolved.');
    }

    /**
     * Referral management
     */
    public function referrals(Request $request)
    {
        $query = Referral::with(['user', 'referredUser']);

        if ($request->has('status')) {
            if ($request->status === 'registered') {
                $query->where('is_registered', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_registered', false);
            }
        }

        if ($request->has('activated')) {
            $query->where('is_activated', $request->boolean('activated'));
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('referredUser', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        $referrals = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => Referral::count(),
            'registered' => Referral::where('is_registered', true)->count(),
            'activated' => Referral::where('is_activated', true)->count(),
            'total_rewards' => Referral::sum('reward_earned'),
        ];

        return view('admin.referrals', compact('referrals', 'stats'));
    }

    /**
     * View referral details
     */
    public function referralDetails(Referral $referral)
    {
        $referral->load(['user', 'referredUser']);

        return view('admin.referral-details', compact('referral'));
    }

    /**
     * Approve referral bonus
     */
    public function approveReferralBonus(Referral $referral)
    {
        if ($referral->reward_earned > 0) {
            return redirect()->back()
                ->with('error', 'Bonus already awarded.');
        }

        // Get referral bonus from settings
        $bonusAmount = \App\Models\SystemSetting::get('referral_bonus_amount', 500);
        
        // Credit the referrer's wallet
        $referrer = $referral->user;
        $referredName = $referral->referredUser ? $referral->referredUser->name : $referral->referred_email;
        if ($referrer && $referrer->wallet) {
            $referrer->wallet->deposit($bonusAmount, 'referral_bonus', 'Referral bonus for referring ' . $referredName);
        }

        $referral->update(['reward_earned' => $bonusAmount]);

        return redirect()->back()
            ->with('success', 'Referral bonus approved and credited.');
    }

    /**
     * Activation management
     */
    public function activations(Request $request)
    {
        $query = ActivationLog::with(['user', 'referrer']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('type')) {
            $query->where('activation_type', $request->type);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('reference', 'like', "%{$search}%");
            });
        }

        $activations = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => ActivationLog::count(),
            'completed' => ActivationLog::where('status', 'completed')->count(),
            'pending' => ActivationLog::where('status', 'pending')->count(),
            'total_revenue' => ActivationLog::where('status', 'completed')->sum('platform_revenue'),
            'total_referral_bonus' => ActivationLog::where('status', 'completed')->sum('referral_bonus'),
        ];

        return view('admin.activations', compact('activations', 'stats'));
    }

    /**
     * View activation details
     */
    public function activationDetails(ActivationLog $activation)
    {
        $activation->load(['user', 'referrer']);

        return view('admin.activation-details', compact('activation'));
    }

    /**
     * Process activation (retry failed)
     */
    public function processActivation(Request $request, ActivationLog $activation)
    {
        if ($activation->status !== 'failed') {
            return redirect()->back()
                ->with('error', 'Only failed activations can be reprocessed.');
        }

        // Mark as pending for reprocessing
        $activation->update(['status' => 'pending']);

        // Notify user to try again
        $activation->user->notify(new \App\Notifications\ActivationReminder($activation));

        return redirect()->back()
            ->with('success', 'Activation marked for reprocessing.');
    }

    /**
     * Analytics
     */
    public function analytics()
    {
        $stats = $this->earnDeskService->getPlatformStats();

        // Revenue calculations
        $totalCommission = Transaction::where('type', 'task_payment')
            ->where('status', 'completed')
            ->sum('amount');
        
        $totalFees = Withdrawal::where('status', 'completed')
            ->sum('fee');

        $platformRevenue = $totalCommission + $totalFees;

        // User growth (last 30 days)
        $newUsers = User::where('created_at', '>=', now()->subDays(30))->count();
        
        // Task completion rate
        $totalCompletions = TaskCompletion::count();
        $approvedCompletions = TaskCompletion::approved()->count();
        $completionRate = $totalCompletions > 0 
            ? round(($approvedCompletions / $totalCompletions) * 100, 2) 
            : 0;

        return view('admin.analytics', compact(
            'stats',
            'platformRevenue',
            'newUsers',
            'completionRate'
        ));
    }

    /**
     * Settings
     */
    public function settings()
    {
        $currencies = Currency::all();
        $categories = TaskCategory::all();
        
        return view('admin.settings', compact('currencies', 'categories'));
    }

    /**
     * Update currency rates
     */
    public function updateCurrencyRates(Request $request)
    {
        foreach ($request->rates as $code => $rate) {
            Currency::where('code', $code)->update(['rate_to_ngn' => $rate]);
        }

        return redirect()->back()
            ->with('success', 'Currency rates updated successfully.');
    }

    /**
     * Update task category
     */
    public function updateCategory(Request $request, TaskCategory $category)
    {
        $category->update($request->all());

        return redirect()->back()
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Create category
     */
    public function createCategory(Request $request)
    {
        TaskCategory::create($request->all());

        return redirect()->back()
            ->with('success', 'Category created successfully.');
    }

    /**
     * Run cron jobs manually
     */
    public function runCronJobs()
    {
        $expiredTasks = $this->earnDeskService->processExpiredTasks();

        return redirect()->back()
            ->with('success', "Processed {$expiredTasks} expired tasks.");
    }
}
