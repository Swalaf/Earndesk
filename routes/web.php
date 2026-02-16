<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Task\CreateTaskController;
use App\Http\Controllers\Admin\RevenueController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StartJourneyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('landing');
})->name('home');

// Short referral link - stores code in session then redirects to register
Route::get('/ref/{code}', [ReferralController::class, 'redirectWithCode'])->name('ref.redirect');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Start Your Journey - Mandatory Task Creation Gate Landing Page
    Route::get('/start-your-journey', [StartJourneyController::class, 'index'])->name('start-your-journey');
    Route::post('/start-your-journey/apply-bundle', [StartJourneyController::class, 'applyBundle'])->name('start-journey.apply-bundle');
    Route::get('/start-your-journey/check-status', [StartJourneyController::class, 'checkUnlockStatus'])->name('start-journey.check-status');
    Route::get('/start-your-journey/success', [StartJourneyController::class, 'unlockSuccess'])->name('start-journey.unlock-success');
    
    // Dashboard sections
    Route::get('/dashboard/worker', [DashboardController::class, 'worker'])->name('dashboard.worker');
    Route::get('/dashboard/client', [DashboardController::class, 'client'])->name('dashboard.client');
    Route::get('/dashboard/leaderboard', [DashboardController::class, 'leaderboard'])->name('dashboard.leaderboard');
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::put('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');

    // Referral routes
    Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals.index');
    Route::post('/referrals/register', [ReferralController::class, 'registerWithCode'])->name('referrals.register');
    Route::post('/referrals/check', [ReferralController::class, 'checkReferral'])->name('referrals.check');

    // Wallet routes
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::get('/activate', [WalletController::class, 'activate'])->name('activate');
        Route::post('/activate', [WalletController::class, 'processActivation'])->name('activate.process');
        Route::get('/deposit', [WalletController::class, 'deposit'])->name('deposit');
        Route::post('/deposit', [WalletController::class, 'deposit'])->name('process-deposit');
        Route::get('/withdraw', [WalletController::class, 'withdraw'])->name('withdraw');
        Route::post('/withdraw', [WalletController::class, 'processWithdrawal'])->name('process-withdrawal');
        Route::get('/balance', [WalletController::class, 'balance'])->name('balance');
        Route::get('/transactions', [WalletController::class, 'transactions'])->name('transactions');
        Route::post('/add-promo', [WalletController::class, 'addPromoCredit'])->name('add-promo');
    });

    // Task routes - Protected by task creation gate middleware
    Route::prefix('tasks')->name('tasks.')->middleware(['task.creation.gate'])->group(function () {
        // New Create Task Module Routes
        Route::get('/create/new', [CreateTaskController::class, 'showCreateForm'])->name('create.new');
        Route::post('/create/store', [CreateTaskController::class, 'store'])->name('create.store');
        Route::post('/create/save-draft', [CreateTaskController::class, 'saveDraft'])->name('create.save-draft');
        Route::get('/create/get-draft', [CreateTaskController::class, 'getDraft'])->name('create.get-draft');
        Route::post('/create/clear-draft', [CreateTaskController::class, 'clearDraft'])->name('create.clear-draft');
        Route::post('/create/refresh-token', [CreateTaskController::class, 'refreshToken'])->name('create.refresh-token');
        Route::post('/create/validate', [CreateTaskController::class, 'validateTaskData'])->name('create.validate');
        Route::get('/create/calculate-cost', [CreateTaskController::class, 'calculateCost'])->name('create.calculate-cost');
        
        // Original task routes
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::get('/create', [TaskController::class, 'create'])->name('create');
        Route::get('/create/resume', [TaskController::class, 'resumeCreate'])->name('create.resume');
        Route::get('/create/saved', [TaskController::class, 'savedCreate'])->name('create.saved');
        Route::post('/create/pay', [TaskController::class, 'payCreate'])->name('create.pay');
        Route::post('/create/save-draft', [TaskController::class, 'saveDraft'])->name('create.save-draft');
        Route::post('/tasks', [TaskController::class, 'store'])->name('store');
        Route::post('/tasks/suggest-bundles', [TaskController::class, 'suggestBundles'])->name('suggest-bundles');
        Route::get('/bundles', [TaskController::class, 'bundles'])->name('bundles');
        Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('my-tasks');
        Route::get('/{task}', [TaskController::class, 'show'])->name('show');
        Route::post('/{task}/submit', [TaskController::class, 'submit'])->name('submit');
        Route::post('/{task}/pause', [TaskController::class, 'pause'])->name('pause');
        Route::post('/{task}/resume', [TaskController::class, 'resume'])->name('resume');
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
        Route::get('/{task}/analytics', [TaskController::class, 'analytics'])->name('analytics');
        Route::put('/{task}', [TaskController::class, 'update'])->name('update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
        
        // Submission review routes
        Route::get('/submission/{completion}', [TaskController::class, 'submissionReview'])->name('submission.review');
        Route::post('/submission/{completion}/approve', [TaskController::class, 'approve'])->name('submission.approve');
        Route::post('/submission/{completion}/reject', [TaskController::class, 'reject'])->name('submission.reject');
        
        // Track platform click analytics
        Route::post('/track-platform-click', [TaskController::class, 'trackPlatformClick'])->name('track-platform-click');
    });

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');

        // Settings routes - admin.settings index for layouts
        Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
        Route::get('/settings/general', [\App\Http\Controllers\SettingsController::class, 'group'])->name('settings.general')->defaults('group', 'general');
        Route::get('/settings/registration', [\App\Http\Controllers\SettingsController::class, 'group'])->name('settings.registration')->defaults('group', 'registration');
        Route::get('/settings/commission', [\App\Http\Controllers\SettingsController::class, 'group'])->name('settings.commission')->defaults('group', 'commission');
        Route::get('/settings/payment', [\App\Http\Controllers\SettingsController::class, 'group'])->name('settings.payment')->defaults('group', 'payment');
        Route::get('/settings/smtp', [\App\Http\Controllers\SettingsController::class, 'group'])->name('settings.smtp')->defaults('group', 'smtp');
        Route::get('/settings/currency', [\App\Http\Controllers\SettingsController::class, 'group'])->name('settings.currency')->defaults('group', 'currency');
        Route::get('/settings/notifications', [\App\Http\Controllers\SettingsController::class, 'notificationMessages'])->name('settings.notifications');
        Route::post('/settings/test-email', [\App\Http\Controllers\SettingsController::class, 'testEmail'])->name('settings.test-email');
        Route::get('/settings/notification', [\App\Http\Controllers\SettingsController::class, 'group'])->name('settings.notification')->defaults('group', 'notification');
        Route::get('/settings/security', [\App\Http\Controllers\SettingsController::class, 'group'])->name('settings.security')->defaults('group', 'security');
        Route::get('/settings/cron', [\App\Http\Controllers\SettingsController::class, 'group'])->name('settings.cron')->defaults('group', 'cron');
        Route::get('/settings/maintenance', [\App\Http\Controllers\SettingsController::class, 'group'])->name('settings.maintenance')->defaults('group', 'maintenance');
        
        // Task Creation Gate settings
        Route::get('/settings/task-gate', [\App\Http\Controllers\SettingsController::class, 'group'])->name('settings.task-gate')->defaults('group', 'task-gate');

        // Generic/grouped admin settings routes
        Route::get('/settings/{group}', [\App\Http\Controllers\SettingsController::class, 'group'])->name('settings.group');
        Route::put('/settings/{group}', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/test-smtp', [\App\Http\Controllers\SettingsController::class, 'testSmtp'])->name('settings.test-smtp');
        Route::post('/settings/test-gateway/{gateway}', [\App\Http\Controllers\SettingsController::class, 'testGateway'])->name('settings.test-gateway');
        Route::post('/settings/trigger-cron/{type}', [\App\Http\Controllers\SettingsController::class, 'triggerCron'])->name('settings.trigger-cron');
        Route::get('/settings/audit/logs', [\App\Http\Controllers\SettingsController::class, 'auditLogs'])->name('settings.audit');
        Route::post('/settings/initialize', [\App\Http\Controllers\SettingsController::class, 'initializeDefaults'])->name('settings.initialize');
        Route::post('/settings/clear-cache', [\App\Http\Controllers\SettingsController::class, 'clearCache'])->name('settings.clear-cache');

        // Analytics
        Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');

        // User management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/{user}', [AdminController::class, 'userDetails'])->name('user-details');
        Route::post('/users/{user}/suspend', [AdminController::class, 'suspendUser'])->name('users.suspend');

        // Task management (admin)
        Route::get('/tasks', [AdminController::class, 'tasks'])->name('tasks');
        Route::get('/tasks/{task}', [AdminController::class, 'taskDetails'])->name('tasks.show');
        Route::post('/tasks/{task}/approve', [AdminController::class, 'approveTask'])->name('tasks.approve');
        Route::post('/tasks/{task}/reject', [AdminController::class, 'rejectTask'])->name('tasks.reject');
        Route::post('/tasks/{task}/feature', [AdminController::class, 'featureTask'])->name('tasks.feature');

        // Withdrawal management
        Route::get('/withdrawals', [AdminController::class, 'withdrawals'])->name('withdrawals');
        Route::post('/withdrawals/{withdrawal}/process', [AdminController::class, 'processWithdrawal'])->name('withdrawals.process');

        // Convenience routes used by admin views for approve/reject actions
        Route::post('/withdrawals/{withdrawal}/approve', [AdminController::class, 'processWithdrawal'])->name('approve-withdrawal');
        Route::post('/withdrawals/{withdrawal}/reject', [AdminController::class, 'processWithdrawal'])->name('reject-withdrawal');

         // end admin routes
    });

    // Cron jobs
    Route::post('/cron', [AdminController::class, 'runCronJobs'])->name('cron');
});

// Additional Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Main admin routes
    Route::get('/', [AdminController::class, 'index'])->name('index');

    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'userDetails'])->name('user-details');
    Route::post('/users/{user}/suspend', [AdminController::class, 'suspendUser'])->name('users.suspend');

    // Task management (admin)
    Route::get('/tasks', [AdminController::class, 'tasks'])->name('tasks');
    Route::get('/tasks/{task}', [AdminController::class, 'taskDetails'])->name('tasks.show');
    Route::post('/tasks/{task}/approve', [AdminController::class, 'approveTask'])->name('tasks.approve');
    Route::post('/tasks/{task}/reject', [AdminController::class, 'rejectTask'])->name('tasks.reject');
    Route::post('/tasks/{task}/feature', [AdminController::class, 'featureTask'])->name('tasks.feature');

    // Analytics
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');

    // Revenue reporting (admin)
    Route::get('/revenue', [\App\Http\Controllers\Admin\RevenueController::class, 'index'])->name('revenue.index');
    Route::get('/revenue/data', [\App\Http\Controllers\Admin\RevenueController::class, 'getRevenueData'])->name('revenue.data');
    Route::get('/revenue/expenses', [\App\Http\Controllers\Admin\RevenueController::class, 'expenses'])->name('revenue.expenses');
    Route::post('/revenue/expenses', [\App\Http\Controllers\Admin\RevenueController::class, 'createExpense'])->name('revenue.expenses.create');
    Route::get('/revenue/activations', [\App\Http\Controllers\Admin\RevenueController::class, 'activations'])->name('revenue.activations');
    Route::get('/revenue/export', [\App\Http\Controllers\Admin\RevenueController::class, 'export'])->name('revenue.export');
    Route::get('/revenue/stats', [\App\Http\Controllers\Admin\RevenueController::class, 'getQuickStats'])->name('revenue.stats');
    Route::match(['get','post'], '/revenue/refresh', [\App\Http\Controllers\Admin\RevenueController::class, 'refresh'])->name('revenue.refresh');
    Route::get('/revenue/chart-data', [\App\Http\Controllers\RevenueApiController::class, 'chartData'])->name('revenue.chart-data');
    Route::get('/revenue/drilldown', [\App\Http\Controllers\RevenueApiController::class, 'drilldown'])->name('revenue.drilldown');

    // Completion review
    Route::get('/completions', [AdminController::class, 'completions'])->name('completions');
    Route::post('/completions/{completion}/approve', [AdminController::class, 'approveCompletion'])->name('completions.approve');
    Route::post('/completions/{completion}/reject', [AdminController::class, 'rejectCompletion'])->name('completions.reject');

    // Withdrawal management
    Route::get('/withdrawals', [AdminController::class, 'withdrawals'])->name('withdrawals');
    Route::post('/withdrawals/{withdrawal}/process', [AdminController::class, 'processWithdrawal'])->name('withdrawals.process');
    Route::post('/withdrawals/{withdrawal}/approve', [AdminController::class, 'processWithdrawal'])->name('approve-withdrawal');
    Route::post('/withdrawals/{withdrawal}/reject', [AdminController::class, 'processWithdrawal'])->name('reject-withdrawal');

    // Fraud logs
    Route::get('/fraud-logs', [AdminController::class, 'fraudLogs'])->name('fraud-logs');
    Route::post('/fraud-logs/{log}/resolve', [AdminController::class, 'resolveFraudLog'])->name('fraud-logs.resolve');

    // Referral management
    Route::get('/referrals', [AdminController::class, 'referrals'])->name('referrals');
    Route::get('/referrals/{referral}', [AdminController::class, 'referralDetails'])->name('referrals.show');
    Route::post('/referrals/{referral}/approve', [AdminController::class, 'approveReferralBonus'])->name('referrals.approve');

    // Activation management
    Route::get('/activations', [AdminController::class, 'activations'])->name('activations');
    Route::get('/activations/{activation}', [AdminController::class, 'activationDetails'])->name('activations.show');
    Route::post('/activations/{activation}/process', [AdminController::class, 'processActivation'])->name('activations.process');
});

// CSRF token endpoint for AJAX keep-alives
Route::get('/_csrf-token', function(){
    return response()->json([
        'token' => csrf_token(),
        'session_lifetime' => config('session.lifetime')
    ]);
});

require __DIR__.'/auth.php';
