@extends('layouts.app')

@section('title', $listing->title . ' - Growth Marketplace')

@section('content')
<div class="py-4 lg:py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('growth.index') }}" class="inline-flex items-center text-indigo-400 hover:text-indigo-300 text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Back to Marketplace
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-dark-900 rounded-2xl shadow-lg border border-dark-700 p-4 lg:p-6">
                    <span class="px-3 py-1 text-xs rounded-full mb-4 inline-block font-medium
                        @switch($listing->type)
                            @case('backlinks') bg-blue-500/20 text-blue-400 @break
                            @case('influencer') bg-pink-500/20 text-pink-400 @break
                            @case('newsletter') bg-purple-500/20 text-purple-400 @break
                            @case('leads') bg-green-500/20 text-green-400 @break
                            @default bg-gray-500/20 text-gray-400 @break
                        @endswitch">
                        <i class="fas 
                            @switch($listing->type)
                                @case('backlinks') fa-link @break
                                @case('influencer') fa-user-check @break
                                @case('newsletter') fa-envelope @break
                                @case('leads') fa-users @break
                                @default fa-tag @break
                            @endswitch mr-1"></i>
                        {{ ucfirst($listing->type) }}
                    </span>

                    <h1 class="text-xl lg:text-2xl font-bold text-white mb-4">{{ $listing->title }}</h1>
                    <p class="text-gray-400 mb-6 text-sm lg:text-base">{{ $listing->description }}</p>

                    @if($listing->specs)
                    @php $specs = is_string($listing->specs) ? json_decode($listing->specs, true) : $listing->specs; @endphp
                    @if(is_array($specs) && count($specs) > 0)
                    <div class="mb-6">
                        <h3 class="font-semibold text-white mb-3">Specifications</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($specs as $key => $value)
                            <div class="p-3 lg:p-4 bg-dark-800 rounded-xl">
                                <div class="text-xs text-gray-500 mb-1">{{ ucwords(str_replace('_', ' ', $key)) }}</div>
                                <div class="font-medium text-white text-sm lg:text-base">{{ is_array($value) ? json_encode($value) : $value }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endif
                    
                    <!-- Seller Info -->
                    <div class="border-t border-dark-700 pt-6 mt-6">
                        <h3 class="font-semibold text-white mb-3">Seller Information</h3>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($listing->user->name ?? 'U', 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-medium text-white">{{ $listing->user->name ?? 'Unknown' }}</p>
                                <p class="text-sm text-gray-400">Member since {{ $listing->user && $listing->user->created_at ? $listing->user->created_at->format('M Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="bg-dark-900 rounded-2xl shadow-lg border border-dark-700 p-4 lg:p-6 sticky top-20">
                    <div class="text-center mb-6">
                        <div class="text-3xl lg:text-4xl font-bold text-green-400 mb-1">₦{{ number_format($listing->price, 2) }}</div>
                        <div class="flex items-center justify-center gap-2 text-sm text-gray-400">
                            <i class="fas fa-clock"></i>
                            <span>{{ $listing->delivery_days }} days delivery</span>
                        </div>
                    </div>

                    @if($listing->status === 'active')
                        <button onclick="createOrder()" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white py-3 rounded-xl font-semibold hover:from-green-600 hover:to-emerald-700 transition-all shadow-lg shadow-green-500/30">
                            <i class="fas fa-shopping-cart mr-2"></i> Order Now
                        </button>
                    @else
                        <div class="w-full bg-dark-800 text-gray-400 py-3 rounded-xl text-center">
                            <i class="fas fa-ban mr-2"></i> Not Available
                        </div>
                    @endif
                    
                    <div class="mt-6 pt-6 border-t border-dark-700">
                        <div class="flex items-center gap-2 text-sm text-gray-400 mb-3">
                            <i class="fas fa-shield-alt text-green-400"></i>
                            <span>Secure payment via escrow</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-400">
                            <i class="fas fa-undo text-blue-400"></i>
                            <span>Money-back guarantee</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function createOrder() {
    try {
        const response = await fetch('{{ route("growth.order", $listing->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        if(data.success) {
            alert('Order placed successfully!');
            window.location.href = data.redirect;
        } else {
            // Check if it's an insufficient balance error
            if(data.message && data.message.toLowerCase().includes('insufficient')) {
                if(confirm(data.message + '\n\nWould you like to deposit funds now?')) {
                    // Store the current page to return to after deposit
                    sessionStorage.setItem('return_after_deposit', window.location.href);
                    sessionStorage.setItem('deposit_amount', {{ $listing->price }});
                    window.location.href = '{{ route("wallet.deposit") }}';
                }
            } else {
                alert(data.message || 'Failed to place order');
            }
        }
    } catch(err) {
        alert('An error occurred. Please try again.');
    }
}
</script>
@endsection
