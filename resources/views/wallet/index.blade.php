@extends('layouts.app')

@section('title', 'My Wallet - EarnDesk')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">My Wallet</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your funds and transactions</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('wallet.deposit') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold hover:from-green-600 hover:to-emerald-700 transition-all shadow-lg shadow-green-500/30">
                    <i class="fas fa-arrow-down mr-2"></i>
                    Deposit
                </a>
                @if($wallet && $wallet->is_activated)
                <a href="{{ route('wallet.withdraw') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-arrow-up mr-2"></i>
                    Withdraw
                </a>
                @endif
            </div>
        </div>

        @if(!$wallet || !$wallet->is_activated)
            <!-- Activation Required -->
            <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-dark-950/50 border border-gray-100 dark:border-dark-700 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-yellow-400 to-orange-500 p-8 text-white">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">Account Activation Required</h2>
                            <p class="opacity-90">Activate your account to start using all features</p>
                        </div>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-500/20 flex items-center justify-center mr-3 mt-0.5">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">Create Tasks</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Post tasks and grow your social media</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-500/20 flex items-center justify-center mr-3 mt-0.5">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">Earn Money</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Complete tasks and get paid</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-500/20 flex items-center justify-center mr-3 mt-0.5">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">Withdraw</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Cash out to your bank account</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('wallet.activate') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-500 hover:to-orange-600 text-white rounded-xl font-bold text-lg shadow-lg transition-all transform hover:scale-[1.02]">
                        <i class="fas fa-rocket mr-2"></i>
                        Activate for ₦1,000
                    </a>
                </div>
            </div>
        @else
            <!-- Balance Card -->
            <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-dark-950/50 border border-gray-100 dark:border-dark-700 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm font-medium opacity-90">Total Balance</p>
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-wallet text-xl"></i>
                        </div>
                    </div>
                    <p class="text-5xl font-bold mb-2">₦{{ number_format($wallet->getTotalBalanceAttribute(), 2) }}</p>
                    <div class="flex items-center gap-4 text-sm opacity-80">
                        <span><i class="fas fa-arrow-up mr-1"></i>₦{{ number_format($wallet->total_earned, 2) }} earned</span>
                        <span><i class="fas fa-arrow-down mr-1"></i>₦{{ number_format($wallet->total_spent, 2) }} spent</span>
                    </div>
                </div>
                <div class="grid grid-cols-3 divide-x divide-gray-200 dark:divide-dark-700">
                    <div class="p-6 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Available</p>
                        <p class="text-xl font-bold text-green-600 dark:text-green-400">₦{{ number_format($wallet->balance, 2) }}</p>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Pending</p>
                        <p class="text-xl font-bold text-orange-600 dark:text-orange-400">₦{{ number_format($wallet->pending_balance, 2) }}</p>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Locked</p>
                        <p class="text-xl font-bold text-gray-600 dark:text-gray-400">₦{{ number_format($wallet->locked_balance, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-lg shadow-gray-200/50 dark:shadow-dark-950/50 border border-gray-100 dark:border-dark-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Earned</h3>
                        <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-500/20 flex items-center justify-center">
                            <i class="fas fa-coins text-green-600 dark:text-green-400"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">₦{{ number_format($wallet->total_earned, 2) }}</div>
                </div>

                <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-lg shadow-gray-200/50 dark:shadow-dark-950/50 border border-gray-100 dark:border-dark-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Spent</h3>
                        <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-500/20 flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-red-600 dark:text-red-400"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">₦{{ number_format($wallet->total_spent, 2) }}</div>
                </div>

                <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-lg shadow-gray-200/50 dark:shadow-dark-950/50 border border-gray-100 dark:border-dark-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Withdrawn</h3>
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center">
                            <i class="fas fa-building text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">₦{{ number_format($wallet->total_withdrawn, 2) }}</div>
                </div>
            </div>

            <!-- Bank Details -->
            <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-lg shadow-gray-200/50 dark:shadow-dark-950/50 border border-gray-100 dark:border-dark-700 p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Bank Details</h2>
                    <a href="{{ route('wallet.activate') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300">
                        <i class="fas fa-pen mr-1"></i> Edit
                    </a>
                </div>
                @if($wallet->bank_name)
                <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-dark-800 rounded-xl">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center">
                        <i class="fas fa-building text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $wallet->bank_name }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $wallet->account_number }} - {{ $wallet->account_name }}</p>
                    </div>
                </div>
                @else
                <p class="text-gray-500 dark:text-gray-400">No bank details added yet.</p>
                @endif
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-lg shadow-gray-200/50 dark:shadow-dark-950/50 border border-gray-100 dark:border-dark-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Recent Transactions</h2>
                    <a href="{{ route('wallet.transactions') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300">View all</a>
                </div>
                @if(count($recentTransactions) > 0)
                <div class="space-y-4">
                    @foreach($recentTransactions as $transaction)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-dark-800 rounded-xl">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $transaction->type === 'credit' ? 'bg-green-100 dark:bg-green-500/20' : 'bg-red-100 dark:bg-red-500/20' }}">
                                <i class="fas {{ $transaction->type === 'credit' ? 'fa-arrow-down text-green-600 dark:text-green-400' : 'fa-arrow-up text-red-600 dark:text-red-400' }}"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $transaction->description }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        <span class="font-bold {{ $transaction->type === 'credit' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $transaction->type === 'credit' ? '+' : '-' }}₦{{ number_format($transaction->amount, 2) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No transactions yet</p>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
