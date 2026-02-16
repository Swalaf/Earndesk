@extends('layouts.admin')

@section('title', 'Cron Jobs')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Cron Job Settings</h1>
                <p class="mt-1 text-sm text-gray-500">Control scheduled task execution</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.settings') }}" class="text-indigo-600 hover:text-indigo-900">
                    ← Back to Settings
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.settings.update', 'cron') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Cron Job Controls -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Scheduled Tasks</h3>
                    <p class="mt-1 text-sm text-gray-500">Enable or disable automatic scheduled tasks</p>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <!-- Task Expiry -->
                    <div class="flex items-center justify-between py-4 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="bg-red-100 rounded-lg p-2 mr-4">
                                <i class="fas fa-clock text-red-600"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Task Expiry Cron</h4>
                                <p class="text-sm text-gray-500">Automatically expire tasks past their end date</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="cron_task_expiry_enabled" value="true"
                                    {{ (($settingsByKey['cron_task_expiry_enabled'] ?? true) === 'true' || $settingsByKey['cron_task_expiry_enabled'] ?? true) ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                            <a href="{{ route('admin.settings.trigger-cron', 'task_expiry') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-play mr-1"></i> Run Now
                            </a>
                        </div>
                    </div>

                    <!-- Referral Bonus -->
                    <div class="flex items-center justify-between py-4 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="bg-green-100 rounded-lg p-2 mr-4">
                                <i class="fas fa-gift text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Referral Bonus Distribution</h4>
                                <p class="text-sm text-gray-500">Distribute referral bonuses when conditions are met</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="cron_referral_bonus_enabled" value="true"
                                    {{ (($settingsByKey['cron_referral_bonus_enabled'] ?? true) === 'true' || $settingsByKey['cron_referral_bonus_enabled'] ?? true) ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                            <a href="{{ route('admin.settings.trigger-cron', 'referral_bonus') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-play mr-1"></i> Run Now
                            </a>
                        </div>
                    </div>

                    <!-- Daily Streak -->
                    <div class="flex items-center justify-between py-4 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="bg-yellow-100 rounded-lg p-2 mr-4">
                                <i class="fas fa-fire text-yellow-600"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Daily Streak Reset</h4>
                                <p class="text-sm text-gray-500">Reset broken streaks and award streak rewards</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="cron_daily_streak_enabled" value="true"
                                    {{ (($settingsByKey['cron_daily_streak_enabled'] ?? true) === 'true' || $settingsByKey['cron_daily_streak_enabled'] ?? true) ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                            <a href="{{ route('admin.settings.trigger-cron', 'daily_streak') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-play mr-1"></i> Run Now
                            </a>
                        </div>
                    </div>

                    <!-- Fraud Scan -->
                    <div class="flex items-center justify-between py-4">
                        <div class="flex items-center">
                            <div class="bg-gray-100 rounded-lg p-2 mr-4">
                                <i class="fas fa-shield-alt text-gray-600"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Fraud Scan</h4>
                                <p class="text-sm text-gray-500">Scan for fraudulent activities and patterns</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="cron_fraud_scan_enabled" value="true"
                                    {{ (($settingsByKey['cron_fraud_scan_enabled'] ?? true) === 'true' || $settingsByKey['cron_fraud_scan_enabled'] ?? true) ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                            <a href="{{ route('admin.settings.trigger-cron', 'fraud_scan') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-play mr-1"></i> Run Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cron Schedule Info -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Setup Instructions:</strong> Add the following to your server's crontab:<br>
                            <code class="bg-blue-100 px-2 py-1 rounded mt-2 inline-block">* * * * * php {{ base_path('artisan') }} schedule:run >> /dev/null 2>&1</code>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    <i class="fas fa-save mr-2"></i> Save Cron Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
