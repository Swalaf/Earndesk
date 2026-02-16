xt@extends('layouts.admin')

@section('title', 'General Settings')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">General Settings</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Configure basic platform settings</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.settings') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-dark-800 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-dark-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Settings
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 dark:bg-green-500/20 border border-green-400 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.settings.update', 'general') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Site Information -->
            <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-lg shadow-gray-200/50 dark:shadow-dark-950/50 border border-gray-100 dark:border-dark-700 mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-dark-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center mr-3">
                            <i class="fas fa-globe text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Site Information</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Basic platform details</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-5 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="site_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-tag mr-2 text-gray-400"></i>Site Name
                            </label>
                            <input type="text" name="site_name" id="site_name"
                                value="{{ old('site_name', $settingsByKey['site_name'] ?? 'EarnDesk') }}"
                                class="w-full rounded-xl border-gray-200 dark:border-dark-700 dark:bg-dark-800 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Your site name">
                        </div>
                        <div>
                            <label for="site_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-link mr-2 text-gray-400"></i>Site URL
                            </label>
                            <input type="url" name="site_url" id="site_url"
                                value="{{ old('site_url', $settingsByKey['site_url'] ?? url('/')) }}"
                                class="w-full rounded-xl border-gray-200 dark:border-dark-700 dark:bg-dark-800 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="https://example.com">
                        </div>
                    </div>
                    <div>
                        <label for="site_logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-image mr-2 text-gray-400"></i>Site Logo URL
                        </label>
                        <input type="url" name="site_logo" id="site_logo"
                            value="{{ old('site_logo', $settingsByKey['site_logo'] ?? '') }}"
                            class="w-full rounded-xl border-gray-200 dark:border-dark-700 dark:bg-dark-800 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="https://example.com/logo.png">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter the URL of your logo image (recommended size: 200x50px)</p>
                    </div>
                </div>
            </div>

            <!-- Platform Settings -->
            <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-lg shadow-gray-200/50 dark:shadow-dark-950/50 border border-gray-100 dark:border-dark-700 mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-dark-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center mr-3">
                            <i class="fas fa-cogs text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Platform Configuration</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Core platform behavior settings</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-5 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="minimum_required_budget" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-coins mr-2 text-gray-400"></i>Minimum Task Budget (NGN)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400">₦</span>
                                </div>
                                <input type="number" step="0.01" name="minimum_required_budget" id="minimum_required_budget"
                                    value="{{ old('minimum_required_budget', $settingsByKey['minimum_required_budget'] ?? '2500') }}"
                                    class="w-full pl-7 pr-4 rounded-xl border-gray-200 dark:border-dark-700 dark:bg-dark-800 focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="2500">
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minimum budget required to create a task</p>
                        </div>
                        <div>
                            <label for="platform_fee_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-percentage mr-2 text-gray-400"></i>Platform Fee (%)
                            </label>
                            <input type="number" step="0.1" name="platform_fee_percentage" id="platform_fee_percentage"
                                value="{{ old('platform_fee_percentage', $settingsByKey['platform_fee_percentage'] ?? '15') }}"
                                class="w-full rounded-xl border-gray-200 dark:border-dark-700 dark:bg-dark-800 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="15">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Percentage taken from each transaction</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="default_task_expiry_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-clock mr-2 text-gray-400"></i>Default Task Expiry (Hours)
                            </label>
                            <input type="number" name="default_task_expiry_hours" id="default_task_expiry_hours"
                                value="{{ old('default_task_expiry_hours', $settingsByKey['default_task_expiry_hours'] ?? '72') }}"
                                class="w-full rounded-xl border-gray-200 dark:border-dark-700 dark:bg-dark-800 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="72">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hours before incomplete tasks expire</p>
                        </div>
                        <div>
                            <label for="max_task_budget" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-money-bill-wave mr-2 text-gray-400"></i>Maximum Task Budget (NGN)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400">₦</span>
                                </div>
                                <input type="number" step="0.01" name="max_task_budget" id="max_task_budget"
                                    value="{{ old('max_task_budget', $settingsByKey['max_task_budget'] ?? '100000') }}"
                                    class="w-full pl-7 pr-4 rounded-xl border-gray-200 dark:border-dark-700 dark:bg-dark-800 focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="100000">
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Maximum budget allowed for a single task</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-save mr-2"></i>Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
