<!DOCTYPE html>
<html lang="en" class="dark no-css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name', 'Earn Desk') }}</title>

    {{-- Critical inline CSS: minimal styles for header, nav and main to avoid FOUC before full CSS loads. --}}
    <style>
        /* Instead of hiding the page, apply immediate dark-mode safe styles to avoid flash
           and ensure the page looks correct before the compiled CSS loads. */
        html.no-css body { background: #0f172a; color: #e5e7eb; visibility: visible; }
        html.no-css .bg-white { background: #1e293b; }
        html.no-css .bg-gray-50 { background: #1e293b; }
        html.no-css .bg-gray-100 { background: #334155; }
        html.no-css .text-gray-900 { color: #f1f5f9; }
        html.no-css .text-gray-700 { color: #e2e8f0; }
        html.no-css .text-gray-600 { color: #cbd5e1; }
        html.no-css .border-gray-200 { border-color: #334155; }
        html.no-css input, html.no-css select, html.no-css textarea { 
            background: #334155; 
            border-color: #475569; 
            color: #f1f5f9; 
        }

        /* Critical header styles so top nav doesn't flash unstyled (dark variant) */
        header { position: fixed; top: 0; left: 0; right: 0; z-index: 50; height: 64px; background: rgba(2,6,23,0.95); backdrop-filter: blur(8px); border-bottom: 1px solid #1e293b; }
        header .logo { display: inline-flex; align-items: center; gap: .5rem; }
        header a, header button { color: #e2e8f0; }
        main { padding-top: 72px; }

        /* Basic typography fallback so text layout is stable before fonts load */
        .font-body, body { font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        /* Hide the theme toggle since dark mode is permanent */
        #theme-toggle { display: none !important; }
        
        /* Form elements immediate styling */
        html.no-css input, html.no-css select, html.no-css textarea {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
        }
        html.no-css button {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            cursor: pointer;
        }
    </style>

    {{-- Preconnect to fonts and preload critical assets to reduce perceived load time --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="{{ mix('css/app.css') }}" as="style">
    <link rel="preload" href="{{ mix('js/app.js') }}" as="script">

    {{-- Use compiled Tailwind CSS (built by Laravel Mix). onload will unhide the page. --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" onload="document.documentElement.classList.remove('no-css'); document.documentElement.classList.add('css-loaded');">
    <noscript>
        <style>html.no-css body{visibility:visible;}</style>
    </noscript>
    <script src="https://unpkg.com/lucide@latest" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    <link id="heading-font-link" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link id="heading-font-link" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"></noscript>
    <link id="body-font-link" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link id="body-font-link" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet"></noscript>
    
    <script>
        (function(){
            var __tdCfg = {
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
            };
            try{
                if (typeof tailwind !== 'undefined') {
                    tailwind.config = __tdCfg;
                } else {
                    // fallback for environments without tailwind-js loaded yet
                    window.__tailwind_config = __tdCfg;
                }
            }catch(e){
                // ignore
            }
        })();
    </script>

    <style>
        /* Ensure no default browser margin so sticky header sits at the top edge */
        body { margin: 0; padding: 0; }
         :root {
            --font-heading-name: 'Plus Jakarta Sans';
            --font-body-name: 'Inter';
        }
        
        /* Dark mode transitions */
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
        
        /* Smooth theme switching */
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        /* Fallback: ensure main respects header height even before JS runs */
        /* Add a small safety offset so content isn't hidden under the fixed header
           if the header is taller than the initial CSS fallback or if JS hasn't run yet */
        main { padding-top: calc(var(--header-height, 64px) + 8px); }

        /* Ensure flash messages are visible and not hidden under the fixed header */
        #flash-messages {
            position: relative;
            z-index: 60; /* above header (header z-50) */
            padding-top: calc(var(--header-height, 64px) + 8px);
            pointer-events: auto;
        }

        /* ============================================
           ENHANCED UI STYLES
           ============================================ */
        
        /* Enhanced Input Fields - Prominent Borders & Better Padding */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="tel"],
        input[type="url"],
        input[type="search"],
        input[type="date"],
        input[type="datetime-local"],
        textarea,
        select {
            padding: 0.75rem 1rem;
            border-width: 2px;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
        }
        
        /* Input focus states - prominent glow */
        input:focus,
        textarea:focus,
        select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            outline: none;
        }
        
        .dark input:focus,
        .dark textarea:focus,
        .dark select:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.15);
        }
        
        /* Input placeholder styling */
        input::placeholder,
        textarea::placeholder {
            color: #9ca3af;
            opacity: 1;
        }
        
        .dark input::placeholder,
        .dark textarea::placeholder {
            color: #6b7280;
        }

        /* Enhanced Toggle Buttons - Always Visible Borders */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 52px;
            height: 28px;
        }
        
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e5e7eb;
            border: 2px solid #9ca3af;
            border-radius: 28px;
            transition: all 0.3s ease;
        }
        
        .toggle-slider:hover {
            border-color: #6b7280;
        }
        
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        input:checked + .toggle-slider {
            background-color: #3b82f6;
            border-color: #2563eb;
        }
        
        input:checked + .toggle-slider:before {
            transform: translateX(24px);
        }
        
        .dark .toggle-slider {
            background-color: #1f2937;
            border: 2px solid #6b7280;
        }
        
        .dark .toggle-slider:hover {
            border-color: #9ca3af;
        }
        
        .dark input:checked + .toggle-slider {
            background-color: #3b82f6;
            border-color: #60a5fa;
        }

        /* Professional Tooltips */
        [data-tooltip] {
            position: relative;
        }
        
        [data-tooltip]::before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(-8px);
            padding: 0.5rem 0.75rem;
            background-color: #1f2937;
            color: white;
            font-size: 0.75rem;
            border-radius: 0.375rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            z-index: 100;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        [data-tooltip]:hover::before {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(-4px);
        }
        
        [data-tooltip].tooltip-top::before {
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(-8px);
        }
        
        [data-tooltip].tooltip-top:hover::before {
            transform: translateX(-50%) translateY(-4px);
        }
        
        [data-tooltip].tooltip-bottom::before {
            top: 100%;
            bottom: auto;
            transform: translateX(-50%) translateY(8px);
        }
        
        [data-tooltip].tooltip-bottom:hover::before {
            transform: translateX(-50%) translateY(4px);
        }
        
        [data-tooltip].tooltip-left::before {
            bottom: auto;
            left: auto;
            right: 100%;
            top: 50%;
            transform: translateY(-50%) translateX(-8px);
        }
        
        [data-tooltip].tooltip-left:hover::before {
            transform: translateY(-50%) translateX(-4px);
        }
        
        [data-tooltip].tooltip-right::before {
            bottom: auto;
            left: 100%;
            top: 50%;
            transform: translateY(-50%) translateX(8px);
        }
        
        [data-tooltip].tooltip-right:hover::before {
            transform: translateY(-50%) translateX(4px);
        }

        /* Enhanced Buttons */
        .btn, button, a.btn, input[type="submit"] {
            padding: 0.625rem 1.25rem;
            border-radius: 0.625rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        /* Button variants */
        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
        }
        
        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #4b5563;
        }
        
        .btn-success {
            background-color: #10b981;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #059669;
        }
        
        .btn-danger {
            background-color: #ef4444;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }

        /* Consistent Form Element Spacing */
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .dark .form-label {
            color: #d1d5db;
        }
        
        .form-helper {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.375rem;
        }
        
        .form-error {
            font-size: 0.75rem;
            color: #ef4444;
            margin-top: 0.375rem;
        }

        /* Card enhancements */
        .card {
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .dark .card {
            border-color: #374151;
        }
        
        .card-hover:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

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

        /* Loading state for buttons */
        button.loading, .btn.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        button.loading::after, .btn.loading::after {
            content: "";
            display: inline-block;
            width: 12px;
            height: 12px;
            margin-left: 8px;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Ripple effect for buttons */
        .btn-ripple {
            position: relative;
            overflow: hidden;
        }

        .btn-ripple::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
        }

        .btn-ripple:active::after {
            width: 200%;
            height: 200%;
        }

        /* Toast notifications - slide in from top */
        .toast {
            animation: slideInTop 0.3s ease-out;
        }

        @keyframes slideInTop {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Card hover effects */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        /* Focus visible styles for accessibility */
        button:focus-visible, .btn:focus-visible,
        a:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible {
            outline: 2px solid #6366f1;
            outline-offset: 2px;
        }
    </style>
    
    <script>
        // Force permanent dark mode immediately to avoid any light flash
        (function(){
            try {
                localStorage.setItem('theme', 'dark');
                document.documentElement.classList.add('dark');
            } catch(e) { /* ignore */ }
        })();
    </script>
</head>
<body class="font-body bg-dark-50 dark:bg-dark-950 text-gray-900 dark:text-gray-100 min-h-screen">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation (fixed to top) -->
        <header class="fixed top-0 left-0 right-0 z-50 bg-white dark:bg-dark-900 border-b border-gray-200 dark:border-dark-700 backdrop-blur-lg bg-white/80 dark:bg-dark-900/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center group">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-ind30 group-hover:shadow-indigo-igo-500/500/50 transition-all">
                                <i class="fas fa-coins text-white text-lg"></i>
                            </div>
                            <span class="font-bold text-xl bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">EarnDesk</span>
                        </a>
                    </div>

                    <!-- Navigation -->
                    <nav class="hidden md:flex space-x-1">
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }} transition-all">
                            <i class="fas fa-home mr-2"></i>Dashboard
                        </a>
                        <a href="{{ route('tasks.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('tasks.*') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }} transition-all">
                            <i class="fas fa-tasks mr-2"></i>Tasks
                        </a>
                        <a href="{{ route('wallet.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('wallet.*') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800' }} transition-all">
                            <i class="fas fa-wallet mr-2"></i>Wallet
                        </a>
                        @if(Auth::check() && Auth::user()->is_admin)
                        <a href="{{ route('admin.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-dark-800 transition-all">
                            <i class="fas fa-cog mr-2"></i>Admin
                        </a>
                        @endif
                    </nav>

                    <!-- Right Side -->
                    <div class="flex items-center space-x-4">
                        <!-- Theme Toggle -->
                        <button id="theme-toggle" class="p-2.5 rounded-xl bg-gray-100 dark:bg-dark-800 hover:bg-gray-200 dark:hover:bg-dark-700 text-gray-600 dark:text-gray-400 transition-all shadow-sm hover:shadow-md" title="Toggle theme">
                            <i class="fas fa-sun dark:hidden text-lg"></i>
                            <i class="fas fa-moon hidden dark:block text-lg"></i>
                        </button>
                        
                        @auth
                            @php
                                $authUser = Auth::user();
                                $wallet = $authUser->wallet ?? null;
                                $balance = $wallet ? ($wallet->withdrawable_balance + $wallet->promo_credit_balance) : 0;
                            @endphp
                            <div class="flex items-center space-x-3">
                                <div class="hidden sm:block text-right">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $authUser->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center justify-end">
                                        <i class="fas fa-naira-sign mr-0.5"></i>{{ number_format($balance, 2) }}
                                    </p>
                                </div>
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-500/30">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2.5 rounded-xl bg-gray-100 dark:bg-dark-800 hover:bg-red-50 dark:hover:bg-red-500/10 text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-all shadow-sm hover:shadow-md" title="Logout">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all">Log in</a>
                            <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-medium transition-all shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50">Get Started</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Alert messages -->
        <div id="flash-messages" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            @if(session('success'))
            <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-100 text-green-700 flex justify-between items-start">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-check-circle text-green-600 mt-1"></i>
                    <div>
                        <p class="font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
                <button onclick="this.closest('.mb-4').remove()" class="text-green-700 hover:text-green-900 ml-4"><i class="fas fa-times"></i></button>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-100 text-red-700 flex justify-between items-start">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-exclamation-circle text-red-600 mt-1"></i>
                    <div>
                        <p class="font-semibold">{{ session('error') }}</p>
                    </div>
                </div>
                <button onclick="this.closest('.mb-4').remove()" class="text-red-700 hover:text-red-900 ml-4"><i class="fas fa-times"></i></button>
            </div>
            @endif

            @if(session('warning'))
            <div class="mb-4 p-4 rounded-lg bg-yellow-50 border border-yellow-100 text-yellow-700 flex justify-between items-start">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1"></i>
                    <div>
                        <p class="font-semibold">{{ session('warning') }}</p>
                    </div>
                </div>
                <button onclick="this.closest('.mb-4').remove()" class="text-yellow-700 hover:text-yellow-900 ml-4"><i class="fas fa-times"></i></button>
            </div>
            @endif

            @if(session('info'))
            <div class="mb-4 p-4 rounded-lg bg-blue-50 border border-blue-100 text-blue-700 flex justify-between items-start">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                    <div>
                        <p class="font-semibold">{{ session('info') }}</p>
                    </div>
                </div>
                <button onclick="this.closest('.mb-4').remove()" class="text-blue-700 hover:text-blue-900 ml-4"><i class="fas fa-times"></i></button>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-100 text-red-700">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-exclamation-circle text-red-600 mt-1"></i>
                    <div>
                        <p class="font-semibold">Please fix the following errors:</p>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="flex-1">
            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="bg-white dark:bg-dark-900 border-t border-gray-200 dark:border-dark-700 py-6 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-2">
                            <i class="fas fa-coins text-white text-sm"></i>
                        </div>
                        <span class="font-bold text-gray-900 dark:text-white">EarnDesk</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">© 2024 EarnDesk. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Theme toggle functionality
        const themeToggle = document.getElementById('theme-toggle');
        
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

        // Load compiled app JS (Alpine, etc.) then initialize icons
    </script>

    {{-- Compiled application JS (Alpine, etc.). Use mix to reference built asset. --}}
    <script src="{{ mix('js/app.js') }}" defer></script>

    <script>
        // Dynamic main padding to account for fixed header height
        (function(){
            function adjustMainPadding(){
                 try{
                     var header = document.querySelector('header');
                     var footer = document.querySelector('footer');
                     var main = document.querySelector('main');
                     if(!header || !main) return;
                     var headerHeight = header.getBoundingClientRect().height || header.offsetHeight || 64;
                     var footerHeight = footer ? (footer.getBoundingClientRect().height || footer.offsetHeight || 0) : 0;

                    // expose CSS variables so pages can use them in calc() for min-height/centering
                    document.documentElement.style.setProperty('--header-height', headerHeight + 'px');
                    document.documentElement.style.setProperty('--footer-height', footerHeight + 'px');

                    // Add a small safety offset to the padding so content won't sit beneath
                    // the fixed header if the header becomes taller (wrapped nav, auth area)
                    var SAFE_OFFSET = 8; // px
                    main.style.paddingTop = (headerHeight + SAFE_OFFSET) + 'px';
                    // ensure main fills remaining viewport so content can vertically center when desired
                    main.style.minHeight = 'calc(100vh - ' + (headerHeight + SAFE_OFFSET) + 'px - ' + footerHeight + 'px)';
                 }catch(e){ /* ignore */ }
             }
            // run on DOM ready and on resize
            document.addEventListener('DOMContentLoaded', adjustMainPadding);
            window.addEventListener('resize', adjustMainPadding);
            // extra call after fonts/assets settle
            setTimeout(adjustMainPadding, 120);
            // call immediately in case DOMContentLoaded already fired
            try { adjustMainPadding(); } catch(e){}
        })();
    </script>

    <script>
        // Initialize Lucide icons - wait for script to load
        function initLucideIcons() {
            if (typeof lucide !== 'undefined' && lucide.createIcons) {
                lucide.createIcons();
            } else {
                // Retry after a short delay if not loaded yet
                setTimeout(initLucideIcons, 100);
            }
        }
        // Start checking for lucide
        initLucideIcons();
    </script>

    {{-- Render page-specific scripts pushed via @push('scripts') --}}
    <script>
        // Auto-dismiss flash messages after 6 seconds
        setTimeout(() => {
            try {
                const container = document.getElementById('flash-messages');
                if (!container) return;
                const msgs = container.querySelectorAll('.mb-4');
                msgs.forEach(m => m.remove());
            } catch (e) {
                // ignore
            }
        }, 6000);
    </script>

    @stack('scripts')
</body>
</html>
