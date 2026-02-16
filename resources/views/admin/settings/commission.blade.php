@extends('layouts.admin')

@section('title', 'Commission Settings')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Commission & Earnings</h1>
                <p class="mt-1 text-sm text-gray-500">Configure platform fees, commission rates, and earnings splits</p>
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

        <form action="{{ route('admin.settings.update', 'commission') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Platform Commission -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Platform Commission</h3>
                    <p class="mt-1 text-sm text-gray-500">Control how much the platform earns from each transaction</p>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label for="platform_commission" class="block text-sm font-medium text-gray-700">
                            Platform Commission (%)
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="platform_commission" id="platform_commission"
                                value="{{ old('platform_commission', $settingsByKey['platform_commission'] ?? 25) }}"
                                min="0" max="100" step="0.01"
                                class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 rounded-md">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            Default: 25%. This is deducted from task budgets before calculating worker rewards.
                        </p>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Example:</strong> With a ₦2,500 task budget and 25% commission:<br>
                                    Platform earns: ₦625 | Worker pool: ₦1,875
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings Split -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Worker Earnings Split</h3>
                    <p class="mt-1 text-sm text-gray-500">How worker earnings are distributed between withdrawable balance and promo credits</p>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="withdrawable_split" class="block text-sm font-medium text-gray-700">
                                Withdrawable Balance (%)
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" name="withdrawable_split" id="withdrawable_split"
                                    value="{{ old('withdrawable_split', $settingsByKey['withdrawable_split'] ?? 80) }}"
                                    min="0" max="100" step="1"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 rounded-md">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">%</span>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Can be withdrawn anytime</p>
                        </div>

                        <div>
                            <label for="promo_credit_split" class="block text-sm font-medium text-gray-700">
                                Promo Credit (%)
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" name="promo_credit_split" id="promo_credit_split"
                                    value="{{ old('promo_credit_split', $settingsByKey['promo_credit_split'] ?? 20) }}"
                                    min="0" max="100" step="1"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 rounded-md">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">%</span>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Can be used for task creation only</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="auto_distribute" name="auto_distribute" value="true"
                            {{ (data_get($settingsByKey, 'auto_distribute', true) === 'true' || data_get($settingsByKey, 'auto_distribute', true)) ? 'checked' : '' }}
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="auto_distribute" class="ml-2 block text-sm text-gray-900">
                            Automatically split earnings on each payout
                        </label>
                    </div>
                </div>
            </div>

            <!-- Minimum Thresholds -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Minimum Thresholds</h3>
                    <p class="mt-1 text-sm text-gray-500">Minimum amounts required for platform operations</p>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="minimum_withdrawal" class="block text-sm font-medium text-gray-700">
                                Minimum Withdrawal (₦)
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₦</span>
                                </div>
                                <input type="number" name="minimum_withdrawal" id="minimum_withdrawal"
                                    value="{{ old('minimum_withdrawal', $settingsByKey['minimum_withdrawal'] ?? 1000) }}"
                                    min="0" step="100"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-8 pr-12 sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div>
                            <label for="minimum_required_budget" class="block text-sm font-medium text-gray-700">
                                Minimum Task Budget (₦)
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₦</span>
                                </div>
                                <input type="number" name="minimum_required_budget" id="minimum_required_budget"
                                    value="{{ old('minimum_required_budget', $settingsByKey['minimum_required_budget'] ?? 2500) }}"
                                    min="0" step="100"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-8 pr-12 sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Withdrawal Fees -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Withdrawal Fees</h3>
                    <p class="mt-1 text-sm text-gray-500">Fees charged on withdrawals</p>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="withdrawal_fee_standard" class="block text-sm font-medium text-gray-700">
                                Standard Withdrawal Fee (%)
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" name="withdrawal_fee_standard" id="withdrawal_fee_standard"
                                    value="{{ old('withdrawal_fee_standard', $settingsByKey['withdrawal_fee_standard'] ?? 5) }}"
                                    min="0" max="100" step="0.01"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 rounded-md">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">%</span>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Takes 1-3 business days</p>
                        </div>

                        <div>
                            <label for="withdrawal_fee_instant" class="block text-sm font-medium text-gray-700">
                                Instant Withdrawal Fee (%)
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" name="withdrawal_fee_instant" id="withdrawal_fee_instant"
                                    value="{{ old('withdrawal_fee_instant', $settingsByKey['withdrawal_fee_instant'] ?? 10) }}"
                                    min="0" max="100" step="0.01"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 rounded-md">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">%</span>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Instant processing</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    <i class="fas fa-save mr-2"></i> Save Commission Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
