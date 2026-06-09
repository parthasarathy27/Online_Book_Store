@extends('layouts.app')

@section('title', 'Checkout - ' . $book->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Back to Details -->
    <div class="mb-8">
        <a href="{{ route('books.show', $book->id) }}" class="text-sm font-semibold text-slate-400 hover:text-white transition-colors flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Back to Book Details</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Shipping Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-panel p-8 sm:p-10 rounded-3xl relative overflow-hidden shadow-2xl">
                <h2 class="text-2xl font-extrabold text-white tracking-tight mb-6 flex items-center space-x-2">
                    <span class="w-2 h-6 bg-brand-500 rounded"></span>
                    <span>Shipping & Client Details</span>
                </h2>

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="p-4 mb-6 rounded-xl bg-rose-950/40 border border-rose-800/50 text-rose-400 text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <p class="flex items-center space-x-1">
                                <span>•</span>
                                <span>{{ $error }}</span>
                            </p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('books.purchase', $book->id) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Client Name -->
                    <div>
                        <label for="client_name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Client Full Name</label>
                        <input type="text" name="client_name" id="client_name" required value="{{ old('client_name', Auth::user()->name) }}" class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm" placeholder="John Doe">
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Street Address</label>
                        <input type="text" name="address" id="address" required value="{{ old('address') }}" class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm" placeholder="123 Main St, Apt 4B">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- City -->
                        <div>
                            <label for="city" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">City</label>
                            <input type="text" name="city" id="city" required value="{{ old('city') }}" class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm" placeholder="New York">
                        </div>

                        <!-- State -->
                        <div>
                            <label for="state" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">State / Province</label>
                            <input type="text" name="state" id="state" required value="{{ old('state') }}" class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm" placeholder="NY">
                        </div>

                        <!-- ZIP -->
                        <div>
                            <label for="zip" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">ZIP / Postal Code</label>
                            <input type="text" name="zip" id="zip" required value="{{ old('zip') }}" class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm" placeholder="10001">
                        </div>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Contact Phone Number</label>
                        <input type="text" name="phone" id="phone" required value="{{ old('phone') }}" class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm" placeholder="+1 (555) 000-0000">
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-800">
                        <a href="{{ route('books.show', $book->id) }}" class="px-5 py-3 border border-slate-700 hover:border-slate-500 text-xs font-bold text-slate-300 rounded-xl transition-all">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-500 text-xs font-bold text-white rounded-xl shadow-lg transition-all">
                            Complete Order & Pay
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="space-y-6">
            <div class="glass-panel p-6 rounded-3xl shadow-xl space-y-6">
                <h3 class="text-lg font-bold text-white">Order Summary</h3>

                <!-- Book details -->
                <div class="flex space-x-4">
                    <div class="w-16 h-24 bg-slate-950 rounded overflow-hidden shadow border border-slate-850 flex-shrink-0">
                        @if($book->image_url)
                            <img src="{{ asset($book->image_url) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-[8px] text-slate-600 font-bold bg-slate-950">Cover</div>
                        @endif
                    </div>
                    <div class="flex-grow">
                        <h4 class="font-bold text-white text-sm line-clamp-2">{{ $book->title }}</h4>
                        <p class="text-xs text-slate-400 mt-1">by {{ $book->author }}</p>
                        <span class="inline-block mt-2 px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-900 border border-slate-800 text-slate-400">
                            {{ $book->category ? $book->category->name : 'Uncategorized' }}
                        </span>
                    </div>
                </div>

                <hr class="border-slate-800">

                <!-- Pricing row -->
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Item Price</span>
                    <span class="text-white font-semibold">${{ number_format($book->price, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Shipping</span>
                    <span class="text-emerald-400 font-medium">Free</span>
                </div>

                <hr class="border-slate-800">

                <div class="flex justify-between items-center">
                    <span class="text-base font-bold text-white">Total</span>
                    <span class="text-2xl font-black text-brand-400">${{ number_format($book->price, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
