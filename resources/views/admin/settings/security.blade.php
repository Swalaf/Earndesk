@extends('layouts.admin')

@section('title', 'Security Settings')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Security Settings</h1>
                <p class="mt-1 text-sm text-gray-500">Configure fraud prevention, rate limiting, and security controls</p>
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

        <form action="{{ route('admin.settings.update', 'security') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Account Security -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Account Security</h3>
                    <p class="mt-1 text-sm text-gray-500">Prevent duplicate accounts and abuse</p>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">IP Tracking</h4>
                            <p class="text-sm text-gray-500">Track IP addresses for user accounts</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="ip_tracking_enabled" value="true"
                                {{ (($settingsByKey['ip_tracking_enabled'] ?? true) === 'true' || $settingsByKey['ip_tracking_enabled'] ?? true) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Device Fingerprinting</h4>
                            <p class="text-sm text-gray-500">Identify unique devices for fraud detection</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="device_fingerprinting_enabled" value="true"
                                {{ (($settingsByKey['device_fingerprinting_enabled'] ?? true) === 'true' || $settingsByKey['device_fingerprinting_enabled'] ?? true) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div>
                        <label for="max_accounts_per_ip" class="block text-sm font-medium text-gray-700">
                            Maximum Accounts per IP
                        </label>
                        <input type="number" name="max_accounts_per_ip" id="max_accounts_per_ip"
                            value="{{ old('max_accounts_per_ip', $settingsByKey['max_accounts_per_ip'] ?? 3) }}"
                            min="1" max="10"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <p class="mt-1 text-sm text-gray-500">Prevent multiple accounts from same IP address</p>
                    </div>
                </div>
            </div>

            <!-- Task Security -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Task Security</h3>
                    <p class="mt-1 text-sm text-gray-500">Prevent fraud in task completion</p>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Self-Task Prevention</h4>
                            <p class="text-sm text-gray-500">Prevent users from completing their own tasks</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="self_task_prevention" value="true"
                                {{ (($settingsByKey['self_task_prevention'] ?? true) === 'true' || $settingsByKey['self_task_prevention'] ?? true) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-3">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Fraud Auto-Flagging</h4>
                            <p class="text-sm text-gray-500">Automatically flag suspicious activity patterns</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="fraud_auto_flagging" value="true"
                                {{ (($settingsByKey['fraud_auto_flagging'] ?? true) === 'true' || $settingsByKey['fraud_auto_flagging'] ?? true) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Rate Limiting -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Rate Limiting</h3>
                    <p class="mt-1 text-sm text-gray-500">Control API and request frequency</p>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Enable Rate Limiting</h4>
                            <p class="text-sm text-gray-500">Limit repeated requests from same IP/user</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="rate_limiting_enabled" value="true"
                                {{ (($settingsByKey['rate_limiting_enabled'] ?? true) === 'true' || $settingsByKey['rate_limiting_enabled'] ?? true) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    <i class="fas fa-save mr-2"></i> Save Security Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
