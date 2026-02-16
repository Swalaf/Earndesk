@extends('layouts.admin')

@section('title', 'Notification Settings')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Notification Settings</h1>
                <p class="mt-1 text-sm text-gray-500">Configure email and system notification preferences</p>
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

        <form action="{{ route('admin.settings.update', 'notification') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Email Notifications -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Email Notifications</h3>
                    <p class="mt-1 text-sm text-gray-500">Control which events trigger email notifications to users</p>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Task Approval</h4>
                            <p class="text-sm text-gray-500">Notify workers when their task submission is approved</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="notify_task_approval" value="true"
                                {{ (data_get($settingsByKey, 'notify_task_approval', true) === 'true' || data_get($settingsByKey, 'notify_task_approval', true)) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Task Rejection</h4>
                            <p class="text-sm text-gray-500">Notify workers when their task submission is rejected</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="notify_task_rejection" value="true"
                                {{ (data_get($settingsByKey, 'notify_task_rejection', true) === 'true' || data_get($settingsByKey, 'notify_task_rejection', true)) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">New Task Bundles</h4>
                            <p class="text-sm text-gray-500">Notify workers when new task bundles are available</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="notify_task_bundle" value="true"
                                {{ (data_get($settingsByKey, 'notify_task_bundle', true) === 'true' || data_get($settingsByKey, 'notify_task_bundle', true)) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Referral Bonus</h4>
                            <p class="text-sm text-gray-500">Notify users when they earn referral bonuses</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="notify_referral_bonus" value="true"
                                {{ (data_get($settingsByKey, 'notify_referral_bonus', true) === 'true' || data_get($settingsByKey, 'notify_referral_bonus', true)) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-3">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Withdrawal Updates</h4>
                            <p class="text-sm text-gray-500">Notify users about withdrawal status changes</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="notify_withdrawal" value="true"
                                {{ (data_get($settingsByKey, 'notify_withdrawal', true) === 'true' || data_get($settingsByKey, 'notify_withdrawal', true)) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Admin Notifications -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Admin Notifications</h3>
                    <p class="mt-1 text-sm text-gray-500">Get notified about important platform events</p>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Large Withdrawals</h4>
                            <p class="text-sm text-gray-500">Notify admin for withdrawals above threshold</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">₦</span>
                            <input type="number" name="large_withdrawal_threshold"
                                value="{{ old('large_withdrawal_threshold', data_get($settingsByKey, 'large_withdrawal_threshold', 50000)) }}"
                                class="w-24 focus:ring-indigo-500 focus:border-indigo-500 block sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="flex items-center justify-between py-3">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Fraud Alerts</h4>
                            <p class="text-sm text-gray-500">Notify admin when suspicious activity is detected</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="admin_fraud_alerts" value="true"
                                {{ (data_get($settingsByKey, 'admin_fraud_alerts', true) === 'true' || data_get($settingsByKey, 'admin_fraud_alerts', true)) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    <i class="fas fa-save mr-2"></i> Save Notification Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
