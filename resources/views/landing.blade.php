<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EarnDesk - Earn Money Completing Tasks</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        dark: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    }
                }
            }
        }
    </script>

    <script>
        // Initialize dark mode
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            if (savedTheme === 'light') {
                document.documentElement.classList.remove('dark');
            } else {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <style>
        :root {
            --font-heading-name: 'Plus Jakarta Sans';
        }
        .gradient-text {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-dark-50 dark:bg-dark-950 text-gray-900 dark:text-gray-100 min-h-screen font-sans">
    <!-- Background Effects -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary-500/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl"></div>
    </div>

    <!-- Header -->
    <header class="sticky top-0 z-50 backdrop-blur-lg bg-white/80 dark:bg-dark-900/80 border-b border-gray-200 dark:border-dark-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center group">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-all">
                            <i class="fas fa-coins text-white text-lg"></i>
                        </div>
                        <span class="ml-3 text-xl font-bold font-heading gradient-text">EarnDesk</span>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <button id="theme-toggle-landing" class="p-2.5 rounded-xl bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-600 dark:text-gray-400 transition-all shadow-sm" title="Toggle theme">
                        <i class="fas fa-sun dark:hidden text-lg"></i>
                        <i class="fas fa-moon hidden dark:block text-lg"></i>
                    </button>
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all">
                        <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative py-20 md:py-32 overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center px-4 py-2 bg-primary-100 dark:bg-primary-500/20 rounded-full mb-6">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                <span class="text-sm font-medium text-primary-700 dark:text-primary-300">Nigeria's #1 Micro-Task Platform</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-heading font-extrabold mb-6">
                <span class="text-gray-900 dark:text-white">Complete Tasks.</span><br>
                <span class="gradient-text">Earn Money.</span>
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto">
                The micro-task marketplace where you earn ₦30 - ₦5,000 per task. 
                Like, follow, share, and review to build your income from anywhere in Nigeria.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all transform hover:scale-105">
                    <i class="fas fa-rocket mr-2"></i>
                    Start Earning
                </a>
            </div>
            
            <!-- Stats -->
            <div class="mt-16 grid grid-cols-3 gap-8 max-w-2xl mx-auto">
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold gradient-text">10K+</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Active Users</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold gradient-text">₦50M+</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Paid Out</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold gradient-text">500+</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tasks Daily</div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-20 bg-white dark:bg-dark-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900 dark:text-white mb-4">How It Works</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400">Start earning in 3 simple steps</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-8 bg-gray-50 dark:bg-dark-800 rounded-2xl hover:shadow-xl transition-all">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-green-500/30">
                        <i class="fas fa-user-plus text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">1. Sign Up</h3>
                    <p class="text-gray-600 dark:text-gray-400">Create your free account and activate with just ₦1,000 to unlock all features</p>
                </div>
                <div class="text-center p-8 bg-gray-50 dark:bg-dark-800 rounded-2xl hover:shadow-xl transition-all">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-500/30">
                        <i class="fas fa-tasks text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">2. Complete Tasks</h3>
                    <p class="text-gray-600 dark:text-gray-400">Browse available tasks, submit your work, and get approved by task owners</p>
                </div>
                <div class="text-center p-8 bg-gray-50 dark:bg-dark-800 rounded-2xl hover:shadow-xl transition-all">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-purple-500/30">
                        <i class="fas fa-money-bill-wave text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">3. Get Paid</h3>
                    <p class="text-gray-600 dark:text-gray-400">Withdraw your earnings directly to your bank account instantly</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Task Types -->
    <section class="py-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900 dark:text-white mb-4">Task Categories</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400">Choose from a variety of tasks</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                <!-- Micro Tasks -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white hover:shadow-xl hover:shadow-indigo-500/30 transition-all transform hover:-translate-y-2 cursor-pointer">
                    <div class="text-4xl mb-4">⚡</div>
                    <h3 class="text-xl font-bold mb-2">Micro Tasks</h3>
                    <p class="text-indigo-100 text-sm">₦30 - ₦250</p>
                    <p class="text-indigo-200 text-xs mt-2">Likes, Comments, Follows</p>
                </div>
                <!-- UGC Tasks -->
                <div class="bg-white dark:bg-dark-900 rounded-2xl p-6 border-2 border-gray-200 dark:border-dark-700 hover:border-purple-400 dark:hover:border-purple-500 transition-all transform hover:-translate-y-2 cursor-pointer">
                    <div class="text-4xl mb-4">🎬</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">UGC / Content</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">₦2,500 - ₦5,000</p>
                    <p class="text-gray-400 dark:text-gray-500 text-xs mt-2">Videos, Reviews, Stories</p>
                </div>
                <!-- Growth Tasks -->
                <div class="bg-white dark:bg-dark-900 rounded-2xl p-6 border-2 border-gray-200 dark:border-dark-700 hover:border-blue-400 dark:hover:border-blue-500 transition-all transform hover:-translate-y-2 cursor-pointer">
                    <div class="text-4xl mb-4">📈</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Growth Tasks</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">₦100 - ₦150</p>
                    <p class="text-gray-400 dark:text-gray-500 text-xs mt-2">Invites, Group Joins</p>
                </div>
                <!-- Premium Tasks -->
                <div class="bg-white dark:bg-dark-900 rounded-2xl p-6 border-2 border-gray-200 dark:border-dark-700 hover:border-yellow-400 dark:hover:border-yellow-500 transition-all transform hover:-translate-y-2 cursor-pointer">
                    <div class="text-4xl mb-4">⭐</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Premium</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">₦500+</p>
                    <p class="text-gray-400 dark:text-gray-500 text-xs mt-2">Level 2+ Required</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-heading font-bold text-white mb-4">Ready to Start Earning?</h2>
            <p class="text-xl text-indigo-100 mb-8">Join thousands of Nigerians already earning on EarnDesk</p>
            <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-white text-indigo-600 font-bold rounded-xl shadow-xl hover:bg-gray-100 transition-all transform hover:scale-105">
                <i class="fas fa-rocket mr-2"></i>
                Create Free Account
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white dark:bg-dark-900 border-t border-gray-200 dark:border-dark-700 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-coins text-white text-sm"></i>
                    </div>
                    <span class="ml-3 font-bold text-gray-900 dark:text-white">EarnDesk</span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">© 2024 EarnDesk. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Theme toggle
        const themeToggle = document.getElementById('theme-toggle-landing');
        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                const html = document.documentElement;
                if (html.classList.contains('dark')) {
                    html.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            });
        }
    </script>
</body>
</html>
