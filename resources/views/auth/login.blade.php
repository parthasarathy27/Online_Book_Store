@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 glass-panel p-8 sm:p-10 rounded-3xl relative overflow-hidden shadow-2xl">
        <div class="absolute inset-0 overflow-hidden pointer-events-none -z-10">
            <div class="absolute -right-10 -top-10 w-48 h-48 bg-brand-500/10 rounded-full blur-[40px]"></div>
        </div>

        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-12 w-12 bg-brand-600 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/20 mb-4">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                Sign In
            </h2>
            <p class="mt-2 text-xs text-slate-400">
                Please sign in to access your dashboard
            </p>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="p-4 rounded-xl bg-rose-950/40 border border-rose-800/50 text-rose-400 text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center space-x-1">
                        <span>•</span>
                        <span>{{ $error }}</span>
                    </p>
                @endforeach
            </div>
        @endif

        <!-- Form -->
        <form class="mt-8 space-y-6" action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="space-y-4 rounded-md">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" class="appearance-none relative block w-full px-4 py-3 border border-slate-800 bg-slate-900 placeholder-slate-500 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm" placeholder="user@bookstore.com">
                </div>
                
                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none relative block w-full px-4 py-3 border border-slate-800 bg-slate-900 placeholder-slate-500 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm" placeholder="••••••••">
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-slate-800 rounded bg-slate-900">
                    <label for="remember" class="ml-2 block text-xs text-slate-300">
                        Remember me
                    </label>
                </div>
            </div>

            <!-- Submit -->
            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white btn-gradient shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                    Sign In
                </button>
            </div>

            <!-- Register link -->
            <div class="text-center mt-4">
                <a href="{{ route('register') }}" class="text-xs font-semibold text-brand-400 hover:text-brand-300 transition-colors">
                    Don't have an account? Sign Up
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
