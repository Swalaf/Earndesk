<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Task;
use App\Models\TaskCompletion;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        // Create users
        $clients = User::factory()->count(3)->create();
        $workers = User::factory()->count(10)->create();

        // Create wallets for everyone
        foreach ($clients as $c) {
            Wallet::factory()->create(['user_id' => $c->id, 'withdrawable_balance' => 50000, 'is_activated' => true, 'activated_at' => now()]);
        }
        foreach ($workers as $w) {
            Wallet::factory()->create(['user_id' => $w->id, 'withdrawable_balance' => 1000, 'is_activated' => true, 'activated_at' => now()]);
        }

        // Create tasks for first client
        $client = $clients->first();
        $tasks = Task::factory()->count(5)->create(['user_id' => $client->id]);

        // Create sample pending completions from workers for the first task
        $firstTask = $tasks->first();
        foreach ($workers->take(5) as $w) {
            TaskCompletion::create(['task_id' => $firstTask->id, 'user_id' => $w->id, 'status' => TaskCompletion::STATUS_PENDING, 'submitted_at' => now()->subDays(2)]);
        }
    }
}
