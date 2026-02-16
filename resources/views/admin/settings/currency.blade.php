@extends('layouts.admin')

@section('title', 'Currency Settings')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Currency Settings</h1>
                <p class="mt-1 text-sm text-gray-500">Configure supported currencies and conversion rates</p>
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

        <form action="{{ route('admin.settings.update', 'currency') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Default Display Currency -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Default Display Currency</h3>
                    <p class="text-sm text-gray-500">Select the primary currency for displaying prices</p>
                </div>
                <div class="px-6 py-4">
                    <div class="max-w-xs">
                        <label for="default_currency" class="block text-sm font-medium text-gray-700">
                            Default Currency
                        </label>
                        <select name="default_currency" id="default_currency" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="NGN" {{ ($settingsByKey['default_currency'] ?? 'NGN') === 'NGN' ? 'selected' : '' }}>NGN (₦)</option>
                            <option value="USD" {{ ($settingsByKey['default_currency'] ?? '') === 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="USDT" {{ ($settingsByKey['default_currency'] ?? '') === 'USDT' ? 'selected' : '' }}>USDT (₮)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Enabled Currencies -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Enabled Currencies</h3>
                    <p class="text-sm text-gray-500">Toggle which currencies are available on the platform</p>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- NGN -->
                        <div class="border rounded-lg p-4 {{ ($settingsByKey['currency_ngn_enabled'] ?? true) === 'true' || $settingsByKey['currency_ngn_enabled'] ?? true ? 'border-green-300 bg-green-50' : 'border-gray-200' }}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-lg font-medium text-gray-900">NGN</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="currency_ngn_enabled" value="true"
                                        {{ (($settingsByKey['currency_ngn_enabled'] ?? true) === 'true' || $settingsByKey['currency_ngn_enabled'] ?? true) ? 'checked' : '' }}
                                        class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                </label>
                            </div>
                            <p class="text-sm text-gray-500">Nigerian Naira</p>
                        </div>

                        <!-- USD -->
                        <div class="border rounded-lg p-4 {{ ($settingsByKey['currency_usd_enabled'] ?? false) === 'true' || $settingsByKey['currency_usd_enabled'] ?? false ? 'border-green-300 bg-green-50' : 'border-gray-200' }}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-lg font-medium text-gray-900">USD</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="currency_usd_enabled" value="true"
                                        {{ (($settingsByKey['currency_usd_enabled'] ?? false) === 'true' || $settingsByKey['currency_usd_enabled'] ?? false) ? 'checked' : '' }}
                                        class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                </label>
                            </div>
                            <p class="text-sm text-gray-500">US Dollar</p>
                        </div>

                        <!-- USDT -->
                        <div class="border rounded-lg p-4 {{ ($settingsByKey['currency_usdt_enabled'] ?? false) === 'true' || $settingsByKey['currency_usdt_enabled'] ?? false ? 'border-green-300 bg-green-50' : 'border-gray-200' }}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-lg font-medium text-gray-900">USDT</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="currency_usdt_enabled" value="true"
                                        {{ (($settingsByKey['currency_usdt_enabled'] ?? false) === 'true' || $settingsByKey['currency_usdt_enabled'] ?? false) ? 'checked' : '' }}
                                        class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                </label>
                            </div>
                            <p class="text-sm text-gray-500">Tether (Crypto)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manual Conversion Rates -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Manual Conversion Rates</h3>
                    <p class="text-sm text-gray-500">Set exchange rates for manual currency conversion</p>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="ngn_to_usd_rate" class="block text-sm font-medium text-gray-700">
                                NGN to USD Rate
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₦</span>
                                </div>
                                <input type="number" step="0.01" name="ngn_to_usd_rate" id="ngn_to_usd_rate"
                                    value="{{ old('ngn_to_usd_rate', $settingsByKey['ngn_to_usd_rate'] ?? '1500') }}"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                    placeholder="1500">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">per $1</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Auto-fetch Rates -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Automatic Rate Fetching</h3>
                    <p class="text-sm text-gray-500">Enable automatic currency rate updates</p>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-center">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="auto_fetch_rates" value="true"
                                {{ (($settingsByKey['auto_fetch_rates'] ?? false) === 'true' || $settingsByKey['auto_fetch_rates'] ?? false) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700">Enable Automatic Rate Fetching</span>
                        </label>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">When enabled, currency rates will be fetched from external APIs (future feature)</p>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i> Save Currency Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
