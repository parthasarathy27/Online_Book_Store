<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Online Book Store') - Premium Storefront</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS via CDN (No Node.js compilation required) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c084fc',
                            400: '#a855f7',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                            950: '#0f0720',
                        }
                    }
                }
            }
        }
    </script>

    
    <!-- AlpineJS for interactive elements -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS for gradients & animations -->
    <style>
        .glass-panel {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.07);
        }
        .text-gradient {
            background: linear-gradient(135deg, #c084fc 0%, #8b5cf6 50%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            box-shadow: 0 0 20px rgba(139, 92, 246, 0.4);
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="font-sans flex flex-col min-h-screen selection:bg-brand-500 selection:text-white">
    <!-- Background glow elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-brand-500/10 rounded-full blur-[100px]"></div>
        <div class="absolute top-1/3 -right-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-purple-500/10 rounded-full blur-[100px]"></div>
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 w-full glass-panel border-b border-slate-800" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <span class="p-2 bg-brand-600 rounded-lg shadow-md shadow-brand-500/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </span>
                        <span class="text-xl font-extrabold tracking-tight text-white">
                            Book<span class="text-brand-400">Store</span>
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="text-sm font-medium transition duration-200 {{ request()->routeIs('home') ? 'text-brand-400' : 'text-slate-300 hover:text-white' }}">Home</a>
                    <a href="{{ route('books.index') }}" class="text-sm font-medium transition duration-200 {{ request()->routeIs('books.*') ? 'text-brand-400' : 'text-slate-300 hover:text-white' }}">Browse Books</a>
                    @auth
                        @if(Auth::user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-amber-400 hover:text-amber-300 transition duration-200">Admin Dashboard</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-amber-400 hover:text-amber-300 transition duration-200">My Purchases</a>
                        @endif
                    @endauth
                </nav>

                <!-- Right Side Actions (Admin Auth) -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <div class="flex items-center space-x-3">
                            <span class="text-xs text-slate-400 bg-slate-900 border border-slate-800 px-3 py-1.5 rounded-full">
                                <i class="fa-solid fa-bolt text-amber-400 mr-1"></i> Logged In
                            </span>
                            <form action="{{ Auth::user()->is_admin ? route('admin.logout') : route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-sm font-medium text-rose-400 hover:text-rose-300 transition duration-200">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white transition duration-200">Login</a>
                            <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-brand-600 hover:bg-brand-500 px-4 py-2 rounded-xl shadow transition duration-200">Register</a>
                        </div>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="flex md:hidden">
                    <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500" aria-expanded="false">
                        <svg class="h-6 h-6" x-show="!open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="h-6 h-6" x-show="open" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden" x-show="open" @click.away="open = false" x-cloak>
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-slate-950 border-t border-slate-900">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'bg-slate-900 text-brand-400' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">Home</a>
                <a href="{{ route('books.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('books.*') ? 'bg-slate-900 text-brand-400' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">Browse Books</a>
                @auth
                    @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-amber-400 hover:bg-slate-900">Admin Dashboard</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-amber-400 hover:bg-slate-900">My Purchases</a>
                    @endif
                    <form action="{{ Auth::user()->is_admin ? route('admin.logout') : route('logout') }}" method="POST" class="block w-full">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-rose-400 hover:bg-slate-900">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:bg-slate-900 hover:text-white">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-brand-400 hover:bg-slate-900">Register</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Success & Error Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-emerald-400 rounded-lg bg-emerald-950/40 border border-emerald-800/50 flex items-center justify-between" x-data="{ show: true }" x-show="show">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-4 text-sm text-rose-400 rounded-lg bg-rose-950/40 border border-rose-800/50 flex items-center justify-between" x-data="{ show: true }" x-show="show">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-400 hover:text-rose-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="glass-panel border-t border-slate-900 mt-16 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:flex sm:items-center sm:justify-between text-slate-400">
            <div class="flex justify-center space-x-6 sm:order-2 mb-4 sm:mb-0">
                <a href="{{ route('home') }}" class="hover:text-white text-sm">Home</a>
                <a href="{{ route('books.index') }}" class="hover:text-white text-sm">Books</a>
                <a href="{{ route('login') }}" class="hover:text-white text-sm">Portal</a>
            </div>
            <div class="mt-8 sm:mt-0 sm:order-1">
                <p class="text-sm">&copy; {{ date('Y') }} Online Book Store. All rights reserved. Powered by Laravel 9 & Tailwind CSS.</p>
            </div>
        </div>
    </footer>
</body>
</html>
