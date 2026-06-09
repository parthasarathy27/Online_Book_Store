@extends('layouts.app')

@section('title', 'Edit Order Details')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center space-x-2">
                <span class="w-2 h-8 bg-brand-600 rounded"></span>
                <span>Edit Order details</span>
            </h1>
            <p class="text-sm text-slate-400 mt-2">Update shipping details for Order #ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}.</p>
        </div>
        <a href="{{ route('books.order_confirmation', $order->id) }}" class="text-xs font-bold text-slate-400 hover:text-white transition-colors">
            Cancel
        </a>
    </div>

    <!-- Edit Box -->
    <div class="glass-panel p-8 sm:p-10 rounded-3xl relative overflow-hidden shadow-2xl">
        <!-- Errors -->
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

        <form action="{{ route('books.orders.update', $order->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Book (Readonly) -->
            <div class="bg-slate-900/50 p-4 rounded-2xl border border-slate-850 mb-6 flex items-center space-x-4">
                <div class="w-12 h-18 bg-slate-950 rounded overflow-hidden shadow border border-slate-800 flex-shrink-0">
                    @if($order->book && $order->book->image_url)
                        <img src="{{ asset($order->book->image_url) }}" alt="{{ $order->book->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-[6px] text-slate-600 font-bold bg-slate-950">Cover</div>
                    @endif
                </div>
                <div>
                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider block">Purchased Book</span>
                    @if($order->book)
                        <h4 class="font-bold text-white text-sm line-clamp-1">{{ $order->book->title }}</h4>
                    @else
                        <h4 class="font-bold text-slate-500 text-sm">[Deleted Book]</h4>
                    @endif
                    <span class="text-xs text-brand-400 font-bold">${{ number_format($order->price, 2) }}</span>
                </div>
            </div>

            <!-- Client Name -->
            <div>
                <label for="client_name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Recipient Name</label>
                <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $order->client_name) }}" required class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm">
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Street Address</label>
                <input type="text" name="address" id="address" value="{{ old('address', $order->address) }}" required class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm">
            </div>

            <!-- City, State, ZIP -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="city" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city', $order->city) }}" required class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm">
                </div>
                <div>
                    <label for="state" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">State</label>
                    <input type="text" name="state" id="state" value="{{ old('state', $order->state) }}" required class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm">
                </div>
                <div>
                    <label for="zip" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">ZIP Code</label>
                    <input type="text" name="zip" id="zip" value="{{ old('zip', $order->zip) }}" required class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm">
                </div>
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Contact Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $order->phone) }}" required class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm">
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-800">
                <a href="{{ route('books.order_confirmation', $order->id) }}" class="px-5 py-3 border border-slate-700 hover:border-slate-500 text-xs font-bold text-slate-300 rounded-xl transition-all">
                    Discard
                </a>
                <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-500 text-xs font-bold text-white rounded-xl shadow-lg transition-all">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
