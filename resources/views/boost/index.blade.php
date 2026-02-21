@extends('layouts.app')

@section('title', 'Boost & Promotion - SwiftKudi')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-pink-600 to-purple-600 bg-clip-text text-transparent">Boost & Promotion</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Boost your listings to get more visibility and orders</p>
        </div>

        <!-- Active Boosts -->
        @if($activeBoosts->count() > 0)
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Your Active Boosts</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($activeBoosts as $boost)
                        <div class="bg-white dark:bg-dark-900 rounded-xl shadow border border-gray-100 dark:border-dark-700 p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-1 bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-400 text-xs font-medium rounded-full">
                                    {{ $boost->package->name }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $boost->expires_at->diffForHumans() }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Target: {{ $boost->target_type }} #{{ $boost->target_id }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Boost Packages -->
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Choose a Boost Package</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($packages as $package)
                <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-lg border border-gray-100 dark:border-dark-700 p-6 hover:border-pink-300 dark:hover:border-pink-500 transition-colors">
                    <div class="text-center mb-4">
                        <h3 class="font-bold text-xl text-gray-900 dark:text-gray-100">{{ $package->name }}</h3>
                        <div class="mt-2">
                            <span class="text-3xl font-bold text-pink-600 dark:text-pink-400">₦{{ number_format($package->price) }}</span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $package->duration_days }} days</p>
                    </div>

                    <ul class="space-y-2 mb-6">
                        @foreach(json_decode($package->features, true) ?? [] as $feature)
                            <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-check text-green-500"></i>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>

                    <button type="button" onclick="selectPackage({{ $package->id }}, '{{ $package->name }}', {{ $package->price }})" class="w-full px-4 py-3 bg-gradient-to-r from-pink-600 to-purple-600 hover:from-pink-700 hover:to-purple-700 text-white font-medium rounded-xl transition-colors">
                        Select Package
                    </button>
                </div>
            @endforeach
        </div>

        <!-- Info Section -->
        <div class="mt-8 bg-gradient-to-r from-pink-50 to-purple-50 dark:from-pink-900/10 dark:to-purple-900/10 rounded-2xl p-6">
            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">How Boosting Works</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center flex-shrink-0">
                        <span class="text-pink-600 dark:text-pink-400 font-bold">1</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-gray-100">Choose a Package</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Select the boost duration and features that suit your needs</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center flex-shrink-0">
                        <span class="text-pink-600 dark:text-pink-400 font-bold">2</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-gray-100">Select Target</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Choose which task, service, or product you want to boost</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center flex-shrink-0">
                        <span class="text-pink-600 dark:text-pink-400 font-bold">3</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-gray-100">Get More Views</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Your listing gets featured and attracts more buyers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectPackage(packageId, packageName, price) {
    // This would open a modal to select the target item
    alert('Package selected: ' + packageName + ' - ₦' + price.toLocaleString());
}
</script>
@endsection
