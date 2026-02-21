<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EarnDesk - Nigeria's #1 Micro-Task Platform | Earn Money Completing Tasks</title>
    <meta name="description" content="EarnDesk is Nigeria's leading micro-task marketplace. Complete simple tasks like likes, follows, shares, and reviews to earn ₦30 - ₦5,000 per task. Start earning today!">
    <meta name="keywords" content="earn money online Nigeria, micro-tasks, freelance Nigeria, side hustle, work from home Nigeria, task marketplace">
    <meta name="author" content="EarnDesk">
    <meta property="og:title" content="EarnDesk - Nigeria's #1 Micro-Task Platform">
    <meta property="og:description" content="Complete tasks. Earn money. Join thousands of Nigerians earning on EarnDesk.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://earndesk.ng">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="EarnDesk - Nigeria's #1 Micro-Task Platform">
    <meta name="twitter:description" content="Complete tasks. Earn money. Join thousands of Nigerians earning on EarnDesk.">
    <link rel="canonical" href="https://earndesk.ng">
    
    {{-- Laravel Mix Assets - Tailwind CSS compiled via Mix --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
    
    {{-- External Libraries --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --font-heading-name: 'Plus Jakarta Sans';
        }
        
        /* Dark mode base styles */
        body {
            background-color: #020617;
            color: #f1f5f9;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .gradient-text-alt {
            background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.3); }
            50% { box-shadow: 0 0 40px rgba(99, 102, 241, 0.6); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delayed { animation: float 6s ease-in-out infinite; animation-delay: 2s; }
        .animate-pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }
        .glass-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(99, 102, 241, 0.2);
        }
        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }
        .review-card {
            transition: all 0.3s ease;
        }
        .review-card:hover {
            transform: translateY(-5px);
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #0f172a;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
</head>
<body class="bg-dark-950 text-gray-100 min-h-screen font-sans">
    <!-- Background Effects -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-float-delayed"></div>
        <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-pink-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/3 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/60 z-40 hidden md:hidden" onclick="closeMobileMenu()"></div>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="fixed top-0 left-0 h-full w-72 bg-dark-900 z-50 transform -translate-x-full transition-transform duration-300 md:hidden">
        <div class="p-4 border-b border-dark-700">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-coins text-white text-lg"></i>
                    </div>
                    <span class="ml-3 text-xl font-bold gradient-text">EarnDesk</span>
                </a>
                <button onclick="closeMobileMenu()" class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-dark-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <nav class="p-4 space-y-2">
            <a href="#features" onclick="closeMobileMenu()" class="flex items-center px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-dark-800 transition-all">
                <i class="fas fa-star mr-3 w-5"></i>Features
            </a>
            <a href="#how-it-works" onclick="closeMobileMenu()" class="flex items-center px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-dark-800 transition-all">
                <i class="fas fa-route mr-3 w-5"></i>How It Works
            </a>
            <a href="#marketplace" onclick="closeMobileMenu()" class="flex items-center px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-dark-800 transition-all">
                <i class="fas fa-store mr-3 w-5"></i>Marketplace
            </a>
            <a href="#reviews" onclick="closeMobileMenu()" class="flex items-center px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-dark-800 transition-all">
                <i class="fas fa-comment mr-3 w-5"></i>Reviews
            </a>
        </nav>
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-dark-700 space-y-3">
            <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-4 py-3 rounded-xl bg-dark-800 text-gray-300 hover:text-white transition-all">
                <i class="fas fa-sign-in-alt mr-2"></i>Sign In
            </a>
            <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-4 py-3 rounded-xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white font-semibold transition-all">
                <i class="fas fa-rocket mr-2"></i>Get Started
            </a>
        </div>
    </div>

    <!-- Header -->
    <header class="sticky top-0 z-50 backdrop-blur-xl bg-dark-900/80 border-b border-dark-700/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg text-gray-400 hover:text-white hover:bg-dark-800" onclick="openMobileMenu()">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center group">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-all">
                            <i class="fas fa-coins text-white text-base sm:text-lg"></i>
                        </div>
                        <span class="ml-2 sm:ml-3 text-lg sm:text-xl font-bold font-heading gradient-text">EarnDesk</span>
                    </a>
                </div>
                <nav class="hidden md:flex items-center gap-6 lg:gap-8">
                    <a href="#features" class="text-sm font-medium text-gray-400 hover:text-indigo-400 transition-all">Features</a>
                    <a href="#how-it-works" class="text-sm font-medium text-gray-400 hover:text-indigo-400 transition-all">How It Works</a>
                    <a href="#marketplace" class="text-sm font-medium text-gray-400 hover:text-indigo-400 transition-all">Marketplace</a>
                    <a href="#reviews" class="text-sm font-medium text-gray-400 hover:text-indigo-400 transition-all">Reviews</a>
                </nav>
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-400 hover:text-indigo-400 transition-all">
                        <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-3 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-700 hover:via-purple-700 hover:to-pink-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all transform hover:scale-105">
                        <span class="hidden sm:inline">Get Started</span>
                        <span class="sm:hidden">Join</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative py-16 md:py-24 lg:py-32 overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center px-4 py-2 bg-indigo-500/20 rounded-full mb-6 border border-indigo-500/30">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                        <span class="text-sm font-medium text-indigo-300">🇳🇬 Nigeria's #1 Micro-Task Platform</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-heading font-extrabold mb-6 leading-tight">
                        <span class="text-white">Complete Tasks.</span><br>
                        <span class="gradient-text">Earn Money.</span><br>
                        <span class="text-white">Live Free.</span>
                    </h1>
                    <p class="text-base sm:text-lg md:text-xl text-gray-400 mb-8 max-w-xl mx-auto lg:mx-0">
                        The micro-task marketplace where you earn <span class="font-semibold text-indigo-400">₦30 - ₦5,000</span> per task. 
                        Like, follow, share, and review to build your income from anywhere in Nigeria.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-700 hover:via-purple-700 hover:to-pink-700 text-white font-bold rounded-xl shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all transform hover:scale-105">
                            <i class="fas fa-rocket mr-2"></i>
                            Start Earning Now
                        </a>
                        <a href="#how-it-works" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 bg-dark-800 text-gray-300 font-bold rounded-xl shadow-lg border-2 border-dark-700 hover:border-indigo-500 transition-all">
                            <i class="fas fa-play-circle mr-2 text-indigo-400"></i>
                            See How It Works
                        </a>
                    </div>
                    
                    <!-- Trust Badges -->
                    <div class="mt-8 lg:mt-10 flex flex-wrap items-center justify-center lg:justify-start gap-4 sm:gap-6">
                        <div class="flex items-center gap-2 text-sm text-gray-400">
                            <i class="fas fa-shield-alt text-green-500"></i>
                            <span>Secure Payments</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-400">
                            <i class="fas fa-bolt text-yellow-500"></i>
                            <span>Instant Withdrawals</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-400">
                            <i class="fas fa-headset text-blue-500"></i>
                            <span>24/7 Support</span>
                        </div>
                    </div>
                </div>
                
                <!-- Hero Image/Stats Card -->
                <div class="relative">
                    <div class="glass-card rounded-3xl p-8 shadow-2xl">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center p-6 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 rounded-2xl">
                                <div class="text-4xl md:text-5xl font-bold gradient-text">10K+</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">Active Users</div>
                            </div>
                            <div class="text-center p-6 bg-gradient-to-br from-green-500/10 to-emerald-500/10 rounded-2xl">
                                <div class="text-4xl md:text-5xl font-bold gradient-text-alt">₦50M+</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">Paid Out</div>
                            </div>
                            <div class="text-center p-6 bg-gradient-to-br from-pink-500/10 to-rose-500/10 rounded-2xl">
                                <div class="text-4xl md:text-5xl font-bold text-pink-500">500+</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">Tasks Daily</div>
                            </div>
                            <div class="text-center p-6 bg-gradient-to-br from-yellow-500/10 to-orange-500/10 rounded-2xl">
                                <div class="text-4xl md:text-5xl font-bold text-yellow-500">4.9★</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">User Rating</div>
                            </div>
                        </div>
                        
                        <!-- Mini Activity Feed -->
                        <div class="mt-6 space-y-3">
                            <div class="flex items-center gap-3 p-3 bg-green-500/10 rounded-xl">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Adaeze just earned ₦2,500</p>
                                    <p class="text-xs text-gray-500">UGC Task • 2 min ago</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-blue-500/10 rounded-xl">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Chukwu just withdrew ₦15,000</p>
                                    <p class="text-xs text-gray-500">Bank Transfer • 5 min ago</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 md:py-20 bg-dark-900/50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-emerald-500/20 rounded-full mb-4">
                    <i class="fas fa-sparkles text-emerald-400 mr-2"></i>
                    <span class="text-sm font-medium text-emerald-300">Powerful Features</span>
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-heading font-bold text-white mb-4">
                    Everything You Need to <span class="gradient-text">Earn More</span>
                </h2>
                <p class="text-base sm:text-lg text-gray-400 max-w-2xl mx-auto">
                    Discover all the ways EarnDesk helps you maximize your earnings and grow your income.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <!-- Feature 1 -->
                <div class="feature-card group p-6 bg-indigo-500/10 rounded-2xl border border-indigo-500/20 hover:shadow-xl hover:shadow-indigo-500/10 transition-all">
                    <div class="feature-icon w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-indigo-500/30 transition-transform">
                        <i class="fas fa-tasks text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Micro Tasks</h3>
                    <p class="text-gray-400">Complete simple tasks like likes, follows, comments, and shares. Earn ₦30 - ₦250 per task instantly.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card group p-6 bg-pink-500/10 rounded-2xl border border-pink-500/20 hover:shadow-xl hover:shadow-pink-500/10 transition-all">
                    <div class="feature-icon w-14 h-14 bg-gradient-to-br from-pink-500 to-rose-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-pink-500/30 transition-transform">
                        <i class="fas fa-video text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">UGC Content</h3>
                    <p class="text-gray-400">Create user-generated content like videos, reviews, and testimonials. Earn ₦2,500 - ₦5,000 per task.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card group p-6 bg-emerald-500/10 rounded-2xl border border-emerald-500/20 hover:shadow-xl hover:shadow-emerald-500/10 transition-all">
                    <div class="feature-icon w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-emerald-500/30 transition-transform">
                        <i class="fas fa-store text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Professional Services</h3>
                    <p class="text-gray-400">Offer your professional skills - design, writing, coding, marketing. Set your own prices and terms.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card group p-6 bg-orange-500/10 rounded-2xl border border-orange-500/20 hover:shadow-xl hover:shadow-orange-500/10 transition-all">
                    <div class="feature-icon w-14 h-14 bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-orange-500/30 transition-transform">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Growth Marketplace</h3>
                    <p class="text-gray-400">Buy and sell growth services - backlinks, influencer promotions, newsletter ads, and verified leads.</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="feature-card group p-6 bg-blue-500/10 rounded-2xl border border-blue-500/20 hover:shadow-xl hover:shadow-blue-500/10 transition-all">
                    <div class="feature-icon w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-blue-500/30 transition-transform">
                        <i class="fas fa-download text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Digital Products</h3>
                    <p class="text-gray-400">Sell digital products - templates, eBooks, courses, plugins, and scripts. Passive income made easy.</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="feature-card group p-6 bg-violet-500/10 rounded-2xl border border-violet-500/20 hover:shadow-xl hover:shadow-violet-500/10 transition-all">
                    <div class="feature-icon w-14 h-14 bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-violet-500/30 transition-transform">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Referral Program</h3>
                    <p class="text-gray-400">Invite friends and earn commission on their tasks. Build your network and earn passively forever.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-16 md:py-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-blue-500/20 rounded-full mb-4">
                    <i class="fas fa-route text-blue-400 mr-2"></i>
                    <span class="text-sm font-medium text-blue-300">Simple Process</span>
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-heading font-bold text-white mb-4">
                    How It <span class="gradient-text">Works</span>
                </h2>
                <p class="text-base sm:text-lg text-gray-400">Start earning in 3 simple steps</p>
            </div>
            
            <div class="relative">
                <!-- Connection Line -->
                <div class="hidden md:block absolute top-1/2 left-0 right-0 h-1 bg-gradient-to-r from-green-400 via-blue-500 to-purple-600 transform -translate-y-1/2 rounded-full"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 relative">
                    <!-- Step 1 -->
                    <div class="relative">
                        <div class="bg-dark-800 rounded-2xl p-6 md:p-8 shadow-xl border-2 border-green-500/30 hover:border-green-400 transition-all">
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                1
                            </div>
                            <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-green-500/30">
                                <i class="fas fa-user-plus text-white text-2xl md:text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3 text-center">Sign Up</h3>
                            <p class="text-gray-400 text-center">Create your free account and activate with just ₦1,000 to unlock all earning features.</p>
                        </div>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="relative">
                        <div class="bg-dark-800 rounded-2xl p-6 md:p-8 shadow-xl border-2 border-blue-500/30 hover:border-blue-400 transition-all">
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                2
                            </div>
                            <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-500/30">
                                <i class="fas fa-tasks text-white text-2xl md:text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3 text-center">Complete Tasks</h3>
                            <p class="text-gray-400 text-center">Browse available tasks, submit your work, and get approved by task owners.</p>
                        </div>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="relative">
                        <div class="bg-dark-800 rounded-2xl p-6 md:p-8 shadow-xl border-2 border-purple-500/30 hover:border-purple-400 transition-all">
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                3
                            </div>
                            <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-purple-500/30">
                                <i class="fas fa-money-bill-wave text-white text-2xl md:text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3 text-center">Get Paid</h3>
                            <p class="text-gray-400 text-center">Withdraw your earnings directly to your bank account instantly.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Marketplace Section -->
    <section id="marketplace" class="py-16 md:py-20 bg-gradient-to-br from-indigo-950/30 via-purple-950/30 to-pink-950/30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-purple-500/20 rounded-full mb-4">
                    <i class="fas fa-store text-purple-400 mr-2"></i>
                    <span class="text-sm font-medium text-purple-300">Marketplace</span>
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-heading font-bold text-white mb-4">
                    Explore Our <span class="gradient-text">Marketplace</span>
                </h2>
                <p class="text-base sm:text-lg text-gray-400 max-w-2xl mx-auto">
                    Multiple ways to earn and grow. From micro-tasks to professional services.
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <!-- Micro Tasks -->
                <div class="group relative overflow-hidden bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white hover:shadow-2xl hover:shadow-indigo-500/30 transition-all transform hover:-translate-y-2 cursor-pointer">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="relative">
                        <div class="text-4xl md:text-5xl mb-4">⚡</div>
                        <h3 class="text-lg md:text-xl font-bold mb-2">Micro Tasks</h3>
                        <p class="text-indigo-100 text-sm mb-4">₦30 - ₦250 per task</p>
                        <ul class="text-xs text-indigo-200 space-y-1">
                            <li><i class="fas fa-check mr-1"></i> Social Media Likes</li>
                            <li><i class="fas fa-check mr-1"></i> Comments & Follows</li>
                            <li><i class="fas fa-check mr-1"></i> App Downloads</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Professional Services -->
                <div class="group relative overflow-hidden bg-dark-800 rounded-2xl p-6 border-2 border-dark-700 hover:border-emerald-500 hover:shadow-2xl transition-all transform hover:-translate-y-2 cursor-pointer">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="relative">
                        <div class="text-4xl md:text-5xl mb-4">💼</div>
                        <h3 class="text-lg md:text-xl font-bold text-white mb-2">Pro Services</h3>
                        <p class="text-gray-400 text-sm mb-4">Set your own price</p>
                        <ul class="text-xs text-gray-400 space-y-1">
                            <li><i class="fas fa-check text-emerald-500 mr-1"></i> Graphic Design</li>
                            <li><i class="fas fa-check text-emerald-500 mr-1"></i> Web Development</li>
                            <li><i class="fas fa-check text-emerald-500 mr-1"></i> Content Writing</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Growth Marketplace -->
                <div class="group relative overflow-hidden bg-dark-800 rounded-2xl p-6 border-2 border-dark-700 hover:border-orange-500 hover:shadow-2xl transition-all transform hover:-translate-y-2 cursor-pointer">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-orange-500/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="relative">
                        <div class="text-4xl md:text-5xl mb-4">📈</div>
                        <h3 class="text-lg md:text-xl font-bold text-white mb-2">Growth</h3>
                        <p class="text-gray-400 text-sm mb-4">Scale your business</p>
                        <ul class="text-xs text-gray-400 space-y-1">
                            <li><i class="fas fa-check text-orange-500 mr-1"></i> Backlinks & SEO</li>
                            <li><i class="fas fa-check text-orange-500 mr-1"></i> Influencer Promo</li>
                            <li><i class="fas fa-check text-orange-500 mr-1"></i> Lead Generation</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Digital Products -->
                <div class="group relative overflow-hidden bg-dark-800 rounded-2xl p-6 border-2 border-dark-700 hover:border-blue-500 hover:shadow-2xl transition-all transform hover:-translate-y-2 cursor-pointer">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="relative">
                        <div class="text-4xl md:text-5xl mb-4">🛒</div>
                        <h3 class="text-lg md:text-xl font-bold text-white mb-2">Digital Products</h3>
                        <p class="text-gray-400 text-sm mb-4">Sell & earn passively</p>
                        <ul class="text-xs text-gray-400 space-y-1">
                            <li><i class="fas fa-check text-blue-500 mr-1"></i> Templates & Themes</li>
                            <li><i class="fas fa-check text-blue-500 mr-1"></i> eBooks & Courses</li>
                            <li><i class="fas fa-check text-blue-500 mr-1"></i> Plugins & Scripts</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section id="reviews" class="py-16 md:py-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-yellow-500/20 rounded-full mb-4">
                    <i class="fas fa-star text-yellow-400 mr-2"></i>
                    <span class="text-sm font-medium text-yellow-300">Testimonials</span>
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-heading font-bold text-white mb-4">
                    What Our <span class="gradient-text">Users Say</span>
                </h2>
                <p class="text-base sm:text-lg text-gray-400">Real stories from real earners</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <!-- Review 1 -->
                <div class="review-card bg-dark-800 rounded-2xl p-6 shadow-lg border border-dark-700">
                    <div class="flex items-center gap-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-400 mb-6">"I started with just the activation fee and now I'm earning over ₦50,000 monthly. EarnDesk changed my life! The tasks are simple and payments are instant."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">A</div>
                        <div>
                            <h4 class="font-bold text-white">Adaeze Okafor</h4>
                            <p class="text-sm text-gray-500">Lagos • Earned ₦180K+</p>
                        </div>
                    </div>
                </div>
                
                <!-- Review 2 -->
                <div class="review-card bg-dark-800 rounded-2xl p-6 shadow-lg border border-dark-700">
                    <div class="flex items-center gap-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-400 mb-6">"As a student, EarnDesk helps me pay my bills without affecting my studies. I do tasks during my free time. Best decision ever!"</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold">C</div>
                        <div>
                            <h4 class="font-bold text-white">Chukwu Emenike</h4>
                            <p class="text-sm text-gray-500">Abuja • Earned ₦95K+</p>
                        </div>
                    </div>
                </div>
                
                <!-- Review 3 -->
                <div class="review-card bg-dark-800 rounded-2xl p-6 shadow-lg border border-dark-700">
                    <div class="flex items-center gap-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-400 mb-6">"I offer graphic design services on EarnDesk and I've gotten over 50 orders. The platform fee is fair and I get paid immediately."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-rose-600 rounded-full flex items-center justify-center text-white font-bold">F</div>
                        <div>
                            <h4 class="font-bold text-white">Fatima Abdullahi</h4>
                            <p class="text-sm text-gray-500">Kano • Earned ₦320K+</p>
                        </div>
                    </div>
                </div>
                
                <!-- Review 4 -->
                <div class="review-card bg-dark-800 rounded-2xl p-6 shadow-lg border border-dark-700">
                    <div class="flex items-center gap-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star-half-alt text-yellow-400"></i>
                    </div>
                    <p class="text-gray-400 mb-6">"The referral program is amazing! I invited my friends and now I earn passive income from their tasks. It's like free money!"</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-600 rounded-full flex items-center justify-center text-white font-bold">O</div>
                        <div>
                            <h4 class="font-bold text-white">Oluwaseun Bakare</h4>
                            <p class="text-sm text-gray-500">Ibadan • Earned ₦75K+</p>
                        </div>
                    </div>
                </div>
                
                <!-- Review 5 -->
                <div class="review-card bg-dark-800 rounded-2xl p-6 shadow-lg border border-dark-700">
                    <div class="flex items-center gap-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-400 mb-6">"I sell my eBooks on EarnDesk's digital products marketplace. Made over ₦100K in my first month! The exposure is incredible."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-full flex items-center justify-center text-white font-bold">T</div>
                        <div>
                            <h4 class="font-bold text-white">Tunde Adebayo</h4>
                            <p class="text-sm text-gray-500">Port Harcourt • Earned ₦150K+</p>
                        </div>
                    </div>
                </div>
                
                <!-- Review 6 -->
                <div class="review-card bg-dark-800 rounded-2xl p-6 shadow-lg border border-dark-700">
                    <div class="flex items-center gap-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-400 mb-6">"I was skeptical at first but decided to try. Now I'm a Level 3 earner with access to premium tasks. Customer support is excellent!"</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">N</div>
                        <div>
                            <h4 class="font-bold text-white">Ngozi Eze</h4>
                            <p class="text-sm text-gray-500">Enugu • Earned ₦210K+</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Trust Indicators -->
            <div class="mt-12 md:mt-16 flex flex-wrap justify-center items-center gap-4 md:gap-8">
                <div class="flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-green-500/10 rounded-full">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span class="text-sm font-medium text-green-400">Verified Payments</span>
                </div>
                <div class="flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-blue-500/10 rounded-full">
                    <i class="fas fa-users text-blue-500"></i>
                    <span class="text-sm font-medium text-blue-400">10,000+ Users</span>
                </div>
                <div class="flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-purple-500/10 rounded-full">
                    <i class="fas fa-award text-purple-500"></i>
                    <span class="text-sm font-medium text-purple-400">4.9/5 Rating</span>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 md:py-20 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
            <h2 class="text-2xl sm:text-3xl md:text-5xl font-heading font-bold text-white mb-4 md:mb-6">Ready to Start Earning?</h2>
            <p class="text-lg md:text-xl text-indigo-100 mb-6 md:mb-8 max-w-2xl mx-auto">Join thousands of Nigerians already earning on EarnDesk. Start your journey to financial freedom today!</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 bg-white text-indigo-600 font-bold rounded-xl shadow-xl hover:bg-gray-100 transition-all transform hover:scale-105">
                    <i class="fas fa-rocket mr-2"></i>
                    Create Free Account
                </a>
                <a href="#how-it-works" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 bg-transparent text-white font-bold rounded-xl border-2 border-white/30 hover:bg-white/10 transition-all">
                    <i class="fas fa-info-circle mr-2"></i>
                    Learn More
                </a>
            </div>
            <p class="mt-6 text-indigo-200 text-sm">No credit card required • Free to join • Start earning immediately</p>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 md:py-20 bg-dark-900/50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-heading font-bold text-white mb-4">
                    Frequently Asked <span class="gradient-text">Questions</span>
                </h2>
            </div>
            
            <div class="space-y-4">
                <details class="group bg-dark-800 rounded-xl p-4 md:p-6 cursor-pointer">
                    <summary class="flex justify-between items-center font-semibold text-white">
                        <span>How do I start earning on EarnDesk?</span>
                        <i class="fas fa-chevron-down text-gray-500 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-4 text-gray-400">Simply create a free account, pay the one-time activation fee of ₦1,000, and start completing available tasks. You can withdraw your earnings anytime.</p>
                </details>
                
                <details class="group bg-dark-800 rounded-xl p-4 md:p-6 cursor-pointer">
                    <summary class="flex justify-between items-center font-semibold text-white">
                        <span>How much can I earn per task?</span>
                        <i class="fas fa-chevron-down text-gray-500 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-4 text-gray-400">Micro-tasks pay ₦30 - ₦250, UGC content tasks pay ₦2,500 - ₦5,000, and professional services can earn you much more depending on your skills and pricing.</p>
                </details>
                
                <details class="group bg-dark-800 rounded-xl p-4 md:p-6 cursor-pointer">
                    <summary class="flex justify-between items-center font-semibold text-white">
                        <span>How do I withdraw my earnings?</span>
                        <i class="fas fa-chevron-down text-gray-500 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-4 text-gray-400">Withdrawals are processed instantly to your Nigerian bank account. Minimum withdrawal is ₦1,000. No hidden fees!</p>
                </details>
                
                <details class="group bg-dark-800 rounded-xl p-4 md:p-6 cursor-pointer">
                    <summary class="flex justify-between items-center font-semibold text-white">
                        <span>Is EarnDesk legit and safe?</span>
                        <i class="fas fa-chevron-down text-gray-500 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-4 text-gray-400">Yes! EarnDesk is a registered Nigerian company with over 10,000 active users and ₦50M+ paid out. We have a 4.9/5 rating from our community.</p>
                </details>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark-950 border-t border-dark-700 py-8 md:py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 mb-8">
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-coins text-white text-sm"></i>
                        </div>
                        <span class="ml-3 font-bold text-white text-lg">EarnDesk</span>
                    </div>
                    <p class="text-gray-400 text-sm">Nigeria's #1 micro-task marketplace. Complete tasks, earn money, live free.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3 md:mb-4">Platform</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#features" class="hover:text-indigo-400 transition-colors">Features</a></li>
                        <li><a href="#how-it-works" class="hover:text-indigo-400 transition-colors">How It Works</a></li>
                        <li><a href="#marketplace" class="hover:text-indigo-400 transition-colors">Marketplace</a></li>
                        <li><a href="#reviews" class="hover:text-indigo-400 transition-colors">Reviews</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3 md:mb-4">Marketplace</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('tasks.index') }}" class="hover:text-indigo-400 transition-colors">Micro Tasks</a></li>
                        <li><a href="{{ route('professional-services.index') }}" class="hover:text-indigo-400 transition-colors">Pro Services</a></li>
                        <li><a href="{{ route('growth.index') }}" class="hover:text-indigo-400 transition-colors">Growth</a></li>
                        <li><a href="{{ route('digital-products.index') }}" class="hover:text-indigo-400 transition-colors">Digital Products</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3 md:mb-4">Connect</h4>
                    <div class="flex gap-3">
                        <a href="#" class="w-9 h-9 md:w-10 md:h-10 bg-dark-800 rounded-lg flex items-center justify-center text-gray-400 hover:text-indigo-400 hover:bg-dark-700 transition-all">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-9 h-9 md:w-10 md:h-10 bg-dark-800 rounded-lg flex items-center justify-center text-gray-400 hover:text-indigo-400 hover:bg-dark-700 transition-all">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-9 h-9 md:w-10 md:h-10 bg-dark-800 rounded-lg flex items-center justify-center text-gray-400 hover:text-indigo-400 hover:bg-dark-700 transition-all">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="w-9 h-9 md:w-10 md:h-10 bg-dark-800 rounded-lg flex items-center justify-center text-gray-400 hover:text-indigo-400 hover:bg-dark-700 transition-all">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-dark-700 pt-6 md:pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-gray-500">© 2024 EarnDesk. All rights reserved.</p>
                <div class="flex gap-4 md:gap-6 mt-4 md:mt-0">
                    <a href="#" class="text-sm text-gray-500 hover:text-gray-400">Privacy Policy</a>
                    <a href="#" class="text-sm text-gray-500 hover:text-gray-400">Terms of Service</a>
                    <a href="#" class="text-sm text-gray-500 hover:text-gray-400">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
