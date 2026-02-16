<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel - EarnDesk' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        heading: ['var(--font-heading-name)', 'sans-serif'],
                        body: ['var(--font-body-name)', 'sans-serif'],
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

    <style>
        :root {
            --font-heading-name: 'Plus Jakarta Sans';
            --font-body-name: 'Inter';
        }
        
        .dark body,
        .dark .bg-white,
        .dark .bg-gray-50,
        .dark .bg-gray-100,
        .dark .border-gray-200,
        .dark .border-gray-300,
        .dark .text-gray-900,
        .dark .text-gray-700,
        .dark .text-gray-600,
        .dark .text-gray-500,
        .dark .text-gray-400 {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }
        
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        /* Dark-mode substitutions for commonly used utility classes in blades */
        .dark .bg-white { background-color: #0b1220 !important; }
        .dark .bg-gray-50 { background-color: #07101b !important; }
        .dark .bg-gray-100 { background-color: #0f172a !important; }
        .dark .text-gray-900 { color: #f8fafc !important; }
        .dark .text-gray-700 { color: #e6eef8 !important; }
        .dark .text-gray-600 { color: #cbd5e1 !important; }
        .dark .text-gray-500 { color: #94a3b8 !important; }
        .dark .text-gray-400 { color: #64748b !important; }
        .dark .border-gray-200 { border-color: #334155 !important; }
        .dark .border-gray-300 { border-color: #475569 !important; }
        .dark .bg-indigo-50 { background-color: rgba(79,70,229,0.06) !important; }
        .dark .bg-green-100 { background-color: rgba(16,185,129,0.06) !important; }
        .dark .bg-red-100 { background-color: rgba(239,68,68,0.06) !important; }
        .dark .bg-yellow-50 { background-color: rgba(250,204,21,0.04) !important; }

        /* Broad fixes for utility classes with opacity or newer Tailwind variants (prevents white patches) */
        .dark [class*="bg-white"] { background-color: #07101b !important; }
        .dark [class*="bg-gray-50"] { background-color: #07101b !important; }
        .dark [class*="bg-gray-100"] { background-color: #0f172a !important; }
        .dark [class*="border-gray-200"] { border-color: #334155 !important; }
        .dark [class*="border-gray-300"] { border-color: #475569 !important; }
        .dark [class*="text-gray-900"] { color: #f8fafc !important; }

        /* Ensure transparent/opacity white backgrounds also adapt */
        .dark [class*="/20"], .dark [class*="/10"], .dark [class*="/30"] {
            /* for classes like bg-white/20, bg-gray-50/10 etc */
            background-color: rgba(11,18,32,0.5) !important;
        }

        /* Dark-mode shadow adjustments to avoid bright halos */
        .dark .shadow, .dark .shadow-lg, .dark .shadow-md { box-shadow: 0 6px 18px rgba(2,6,23,0.6) !important; }

        /* Ensure form controls (including newer Tailwind classes) render correctly */
        .dark input[type="text"],
        .dark input[type="email"],
        .dark input[type="password"],
        .dark input[type="number"],
        .dark select,
        .dark textarea,
        .dark .form-control {
            background-color: #07101b !important;
            color: #e6eef8 !important;
            border-color: #334155 !important;
        }

        /* Form controls in dark mode: inputs, selects, textareas, buttons */
        .dark input[type="text"],
        .dark input[type="email"],
        .dark input[type="password"],
        .dark input[type="number"],
        .dark select,
        .dark textarea {
            background-color: #07101b !important;
            color: #e6eef8 !important;
            border-color: #334155 !important;
        }

        /* Ensure input borders are always visible in dark mode (default state) */
        .dark input:not([type="checkbox"]):not([type="radio"]),
        .dark select,
        .dark textarea {
            border-width: 1px !important;
            border-style: solid !important;
            border-color: #334155 !important;
        }

        /* Input focus state */
        .dark input:focus,
        .dark select:focus,
        .dark textarea:focus {
            border-color: #6366f1 !important;
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        /* Toggle switch (checkbox) styling for dark mode */
        .dark input[type="checkbox"].peer,
        .dark input[type="checkbox"][class*="peer"] {
            background-color: #1e293b;
            border-color: #475569;
        }
        
        .dark .peer:checked ~ .peer-checked\:bg-indigo-600 {
            background-color: #4f46e5 !important;
        }
        
        .dark .peer:checked ~ .peer-checked\:after {
            border-color: #ffffff;
        }
        
        .dark .peer ~ .peer-checked\:after {
            background-color: #1e293b;
            border-color: #475569;
        }

        /* Toggle switch alternative - inline styles */
        .dark input[type="checkbox"] {
            accent-color: #4f46e5;
            background-color: #1e293b;
            border-color: #475569;
        }
        
        .dark input[type="checkbox"]:checked {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        /* Label styling for dark mode */
        .dark label {
            color: #e2e8f0;
        }

        .dark input::placeholder,
        .dark textarea::placeholder {
            color: #94a3b8 !important;
            opacity: 1;
        }

        .dark input[readonly],
        .dark input[disabled],
        .dark select[disabled],
        .dark textarea[disabled] {
            background-color: #0b1220 !important;
            opacity: 0.9 !important;
            color: #94a3b8 !important;
        }

        /* Buttons: ensure primary buttons remain visible in dark mode */
        .dark .bg-indigo-600 { background-color: #4f46e5 !important; }
        .dark .bg-indigo-700 { background-color: #4338ca !important; }
        .dark .text-white { color: #ffffff !important; }

        /* Table rows/cards */
        .dark table { color: #e6eef8; }
        .dark thead th { color: #cbd5e1; }

        /* A simple card helper to standardize look */
        .card { background-color: #ffffff; box-shadow: 0 6px 18px rgba(2,6,23,0.08); border-radius: .5rem; }
        .dark .card { background-color: #07101b !important; box-shadow: 0 6px 18px rgba(2,6,23,0.6) !important; }

        /* Pagination / navigation: Laravel's Tailwind pagination uses bg-white/bg-gray-50
           override those specifically for dark mode to avoid white pills */
        .dark nav[role="navigation"],
        .dark nav[role="navigation"] *,
        .dark .pagination,
        .dark .pagination * {
            background-color: transparent !important;
            color: #e6eef8 !important;
            border-color: #24303f !important;
            box-shadow: none !important;
        }

        /* Target the typical pagination links output by Laravel (anchors/spans) */
        .dark nav[role="navigation"] a,
        .dark nav[role="navigation"] span,
        .dark .pagination a,
        .dark .pagination span {
            background-color: #07101b !important;
            color: #e6eef8 !important;
            border-color: #24303f !important;
            padding: .45rem .75rem !important;
            border-radius: .375rem !important;
        }

        /* Fallback for utility classes that include bg-white or gray variants */
        .dark [class*="bg-white"],
        .dark [class*="bg-gray-50"],
        .dark [class*="bg-gray-100"] {
            background-color: #07101b !important;
        }

        /* Ensure dropdowns, selects and other form-related wrappers also adapt */
        .dark .relative .bg-white, .dark .relative [class*="bg-white"] { background-color: #07101b !important; }

        /* Button click responsiveness - better UX */
        button, .btn, a.btn, input[type="submit"] {
            transform: translateZ(0);
            -webkit-transform: translateZ(0);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Button active/click state - scale down slightly for tactile feedback */
        button:active, .btn:active, button:focus, .btn:focus,
        a.btn:active, a.btn:focus,
        input[type="submit"]:active, input[type="submit"]:focus {
            transform: scale(0.97);
            -webkit-transform: scale(0.97);
        }

        /* Smooth transitions for buttons */
        button, .btn, a.btn {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Focus visible styles for accessibility */
        button:focus-visible, .btn:focus-visible,
        a:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible {
            outline: 2px solid #6366f1;
            outline-offset: 2px;
        }
    </style>
    
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            if (savedTheme === 'light') {
                document.documentElement.classList.remove('dark');
            } else {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="font-body bg-dark-50 dark:bg-dark-950 text-gray-900 dark:text-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-dark-900 border-r border-gray-200 dark:border-dark-700 flex flex-col fixed h-full">
            <!-- Logo -->
            <div class="h-16 flex items-center px-6 border-b border-gray-200 dark:border-dark-700">
                <a href="{{ route('dashboard') }}" class="flex items-center group">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-coins text-white text-sm"></i>
                    </div>
                    <span class="font-bold text-lg bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">EarnDesk</span>
                </a>
            </div>

            <!-- Admin Badge -->
            <div class="px-4 py-3 border-b border-gray-200 dark:border-dark-700">
                <div class="flex items-center px-3 py-2 bg-indigo-50 dark:bg-indigo-500/10 rounded-lg">
                    <i class="fas fa-shield-alt text-indigo-600 dark:text-indigo-400 mr-3"></i>
                    <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Admin Panel</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-3">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('admin.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.index') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }}">
                            <i class="fas fa-home w-5 mr-2"></i>
                            Dashboard
                        </a>
                    </li>

                    <!-- Analytics -->
                    <li>
                        <a href="{{ route('admin.analytics') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.analytics') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }}">
                            <i class="fas fa-chart-line w-5 mr-2"></i>
                            Analytics
                        </a>
                    </li>

                    <!-- Revenue -->
                    <li>
                        <a href="{{ route('admin.revenue.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.revenue*') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }}">
                            <i class="fas fa-chart-pie w-5 mr-2"></i>
                            Revenue
                        </a>
                    </li>

                    <li class="pt-4 pb-2">
                        <span class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</span>
                    </li>

                    <!-- Users -->
                    <li>
                        <a href="{{ route('admin.users') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.users') || request()->routeIs('admin.user-details') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }}">
                            <i class="fas fa-users w-5 mr-2"></i>
                            Users
                        </a>
                    </li>

                    <!-- Tasks -->
                    <li>
                        <a href="{{ route('admin.tasks') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.tasks') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }}">
                            <i class="fas fa-tasks w-5 mr-2"></i>
                            Tasks
                        </a>
                    </li>

                    <!-- Completions -->
                    <li>
                        <a href="{{ route('admin.completions') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.completions') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }}">
                            <i class="fas fa-check-circle w-5 mr-2"></i>
                            Completions
                        </a>
                    </li>

                    <!-- Withdrawals -->
                    <li>
                        <a href="{{ route('admin.withdrawals') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.withdrawals') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }}">
                            <i class="fas fa-money-bill-wave w-5 mr-2"></i>
                            Withdrawals
                        </a>
                    </li>

                    <!-- Fraud Logs -->
                    <li>
                        <a href="{{ route('admin.fraud-logs') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.fraud-logs') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }}">
                            <i class="fas fa-exclamation-triangle w-5 mr-2"></i>
                            Fraud Logs
                        </a>
                    </li>

                    <!-- Referrals -->
                    <li>
                        <a href="{{ route('admin.referrals') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.referrals*') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }}">
                            <i class="fas fa-user-friends w-5 mr-2"></i>
                            Referrals
                        </a>
                    </li>

                    <!-- Activation -->
                    <li>
                        <a href="{{ route('admin.activations') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.activations*') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }}">
                            <i class="fas fa-rocket w-5 mr-2"></i>
                            Activations
                        </a>
                    </li>

                    <li class="pt-4 pb-2">
                        <span class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">System Settings</span>
                    </li>

                    <!-- General Settings -->
                    <li>
                        <a href="{{ route('admin.settings') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.settings*') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }}">
                            <i class="fas fa-cog w-5 mr-2"></i>
                            General
                        </a>
                    </li>

                    <!-- Commission Settings -->
                    <li>
                        <a href="{{ route('admin.settings.commission') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.settings.commission') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }}">
                            <i class="fas fa-percentage w-5 mr-2"></i>
                            Commission
                        </a>
                    </li>

                    <!-- Payment Settings -->
                    <li>
                        <a href="{{ route('admin.settings.payment') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.settings.payment') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }}">
                            <i class="fas fa-credit-card w-5 mr-2"></i>
                            Payment
                        </a>
                    </li>

                    <!-- Notification Settings -->
                    <li>
                        <a href="{{ route('admin.settings.notification') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.settings.notification') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }}">
                            <i class="fas fa-bell w-5 mr-2"></i>
                            Notifications
                        </a>
                    </li>

                    <!-- Security Settings -->
                    <li>
                        <a href="{{ route('admin.settings.security') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.settings.security') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }}">
                            <i class="fas fa-shield-alt w-5 mr-2"></i>
                            Security
                        </a>
                    </li>

                    <!-- Cron Jobs -->
                    <li>
                        <a href="{{ route('admin.settings.cron') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.settings.cron') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }}">
                            <i class="fas fa-clock w-5 mr-2"></i>
                            Cron Jobs
                        </a>
                    </li>

                    <!-- Audit Logs -->
                    <li>
                        <a href="{{ route('admin.settings.audit') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.settings.audit') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }}">
                            <i class="fas fa-history w-5 mr-2"></i>
                            Audit Logs
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Bottom Section -->
            <div class="border-t border-gray-200 dark:border-dark-700 p-4">
                <!-- Theme Toggle -->
                <button id="admin-theme-toggle" class="w-full flex items-center justify-center px-4 py-2 mb-3 rounded-lg bg-gray-100 dark:bg-dark-800 text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all">
                    <i class="fas fa-sun dark:hidden mr-2"></i>
                    <i class="fas fa-moon hidden dark:block mr-2"></i>
                    <span class="text-sm">Toggle Theme</span>
                </button>

                <!-- Back to Site -->
                <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span class="text-sm">Back to Site</span>
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        <span class="text-sm">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <!-- Top Header -->
            <header class="h-16 bg-white dark:bg-dark-900 border-b border-gray-200 dark:border-dark-700 flex items-center justify-between px-6 sticky top-0 z-40">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $title ?? 'Admin Panel' }}</h1>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Quick Settings Link -->
                    <a href="{{ route('admin.settings') }}" title="Settings" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-800">
                        <i class="fas fa-cog w-4 mr-2"></i>
                        <span class="hidden sm:inline">Settings</span>
                    </a>

                    <!-- Admin User Info -->
                    <div class="flex items-center space-x-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Administrator</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Theme toggle functionality
        const themeToggle = document.getElementById('admin-theme-toggle');
        
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

        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>
