<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">E</span>
                        </div>
                        <span class="ml-2 text-xl font-bold text-gray-900">EarnDesk</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>

                    <x-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">
                        Tasks
                    </x-nav-link>

                    <x-nav-link :href="route('tasks.bundles')" :active="request()->routeIs('tasks.bundles')">
                        Bundles
                    </x-nav-link>

                    <x-nav-link :href="route('referrals.index')" :active="request()->routeIs('referrals.*')">
                        Referrals
                    </x-nav-link>

                    <x-nav-link :href="route('dashboard.leaderboard')" :active="request()->routeIs('dashboard.leaderboard')">
                        Leaderboard
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                <div class="ml-3 relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                    <div @click="open = ! open" class="flex items-center cursor-pointer">
                        <button class="flex text-sm border-none focus:outline-none focus:border-none transition">
                            <div class="text-right mr-2">
                                <div class="font-medium text-gray-900">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">
                                    Level {{ Auth::user()->level }} • {{ number_format(Auth::user()->experience_points) }} XP
                                </div>
                            </div>
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </button>
                    </div>

                    <div x-show="open" x-transition.opacity.duration.200ms @click="open = false"
                        class="fixed inset-0 z-10" style="display: none;"></div>

                    <div x-show="open" x-transition.opacity.duration.200ms
                        class="absolute right-0 z-20 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 focus:outline-none"
                        style="display: none;" @click="open = false">
                        <!-- Wallet Balance -->
                        @if(Auth::user()->wallet)
                        <div class="px-4 py-2 border-b border-gray-100">
                            <div class="text-xs text-gray-500">Balance</div>
                            <div class="font-medium text-green-600">
                                ₦{{ number_format(Auth::user()->wallet->withdrawable_balance + Auth::user()->wallet->promo_credit_balance, 2) }}
                            </div>
                        </div>
                        @endif

                        <!-- Dashboard -->
                        <x-dropdown-link :href="route('dashboard.profile')">
                            <i class="fas fa-user mr-2"></i> Profile
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('wallet.index')">
                            <i class="fas fa-wallet mr-2"></i> Wallet
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('tasks.my-tasks')">
                            <i class="fas fa-list mr-2"></i> My Tasks
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('referrals.index')">
                            <i class="fas fa-users mr-2"></i> Referrals
                        </x-dropdown-link>

                        <!-- Admin Link -->
                        @if(Auth::user()->is_admin)
                        <x-dropdown-link :href="route('admin.index')">
                            <i class="fas fa-cog mr-2"></i> Admin Panel
                        </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <span class="sr-only">Open main menu</span>
                    <i class="fas fa-bars" x-show="!open"></i>
                    <i class="fas fa-times" x-show="open"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">
                Tasks
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('referrals.index')" :active="request()->routeIs('referrals.*')">
                Referrals
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard.leaderboard')" :active="request()->routeIs('dashboard.leaderboard')">
                Leaderboard
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-4 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                        <span class="text-indigo-600 font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                </div>
                <div class="ml-3">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('wallet.index')">
                    <i class="fas fa-wallet mr-2"></i> Wallet
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dashboard.profile')">
                    <i class="fas fa-user mr-2"></i> Profile
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('tasks.my-tasks')">
                    <i class="fas fa-list mr-2"></i> My Tasks
                </x-responsive-nav-link>

                @if(Auth::user()->email === 'admin@earndesk.com')
                <x-responsive-nav-link :href="route('admin.index')">
                    <i class="fas fa-cog mr-2"></i> Admin Panel
                </x-responsive-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
