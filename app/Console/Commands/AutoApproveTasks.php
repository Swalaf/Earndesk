<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaskCompletion;
use App\Services\EarnDeskService;
use Illuminate\Support\Facades\Log;

class AutoApproveTasks extends Command
{
    protected $signature = 'tasks:auto-approve';
    protected $description = 'Auto-approve task completions pending for > 48 hours and process payouts';

    protected $earnDeskService;

    public function __construct(EarnDeskService $earnDeskService)
    {
        parent::__construct();
        $this->earnDeskService = $earnDeskService;
    }

    public function handle()
    {
        $threshold = now()->subHours(48);
        $pending = TaskCompletion::where('status', TaskCompletion::STATUS_PENDING)
                    ->where('submitted_at', '<=', $threshold)
                    ->get();

        foreach ($pending as $completion) {
            try {
                Log::info('Auto-approving completion', ['id' => $completion->id]);
                $this->earnDeskService->awardTaskEarnings($completion);
            } catch (\Exception $e) {
                Log::error('Auto-approve failed for completion '.$completion->id, ['error' => $e->getMessage()]);
            }
        }

        return 0;
    }
}
