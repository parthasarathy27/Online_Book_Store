@extends('layouts.app')

@section('title', 'Order Confirmation - #' . $order->id)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Success Banner -->
    <div class="text-center mb-12">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-950/80 text-emerald-400 border border-emerald-800 rounded-full mb-6 shadow-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">Thank you for your order!</h1>
        <p class="text-slate-400 mt-2">Your order has been placed successfully. Below are your order confirmation details.</p>
    </div>

    <!-- Details Box -->
    <div class="glass-panel p-8 sm:p-10 rounded-3xl relative overflow-hidden shadow-2xl space-y-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-800 pb-6">
            <div>
                <span class="text-xs text-slate-500 font-bold uppercase tracking-wider block">Order Number</span>
                <span class="text-lg font-black text-brand-400">#ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div>
                <span class="text-xs text-slate-500 font-bold uppercase tracking-wider block">Order Date</span>
                <span class="text-sm font-semibold text-slate-300">{{ $order->created_at->format('M d, Y h:i A') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Book Details -->
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Book Details</h3>
                <div class="flex space-x-4 bg-slate-900/50 p-4 rounded-2xl border border-slate-850">
                    <div class="w-16 h-24 bg-slate-950 rounded overflow-hidden shadow border border-slate-800 flex-shrink-0">
                        @if($order->book && $order->book->image_url)
                            <img src="{{ asset($order->book->image_url) }}" alt="{{ $order->book->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-[8px] text-slate-600 font-bold bg-slate-950">Cover</div>
                        @endif
                    </div>
                    <div class="flex-grow">
                        @if($order->book)
                            <h4 class="font-bold text-white text-sm line-clamp-2">{{ $order->book->title }}</h4>
                            <p class="text-xs text-slate-400 mt-1">by {{ $order->book->author }}</p>
                        @else
                            <h4 class="font-bold text-slate-500 text-sm">[Deleted Book]</h4>
                        @endif
                        <span class="inline-block mt-3 px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-950 border border-slate-800 text-slate-400">
                            {{ $order->book && $order->book->category ? $order->book->category->name : 'Uncategorized' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Shipping Destination -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Shipping Destination</h3>
                    <a href="{{ route('books.orders.edit', $order->id) }}" class="text-xs font-bold text-brand-400 hover:text-brand-300 transition-colors">
                        Edit
                    </a>
                </div>
                <div class="bg-slate-900/50 p-4 rounded-2xl border border-slate-850 space-y-2 text-sm text-slate-300">
                    <div class="font-semibold text-white">{{ $order->client_name }}</div>
                    <div>{{ $order->address }}</div>
                    <div>{{ $order->city }}, {{ $order->state }} {{ $order->zip }}</div>
                    <div class="text-xs text-slate-500 pt-1 border-t border-slate-800">Phone: {{ $order->phone }}</div>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-800 pt-6 space-y-4">
            <div class="flex justify-between items-center bg-slate-900/30 px-6 py-4 rounded-2xl border border-slate-850">
                <span class="text-sm font-bold text-slate-300">Payment Status</span>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-950/80 text-emerald-400 border border-emerald-800">Paid</span>
            </div>
            <div class="flex justify-between items-center bg-slate-900/30 px-6 py-4 rounded-2xl border border-slate-850">
                <span class="text-sm font-bold text-slate-300">Order & Delivery Status</span>
                @if($order->status === 'pending')
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-800 text-slate-300 border border-slate-700">Pending</span>
                @elseif($order->status === 'confirmed')
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-950/80 text-blue-400 border border-blue-800">Confirmed</span>
                @elseif($order->status === 'shipped')
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-950/80 text-amber-400 border border-amber-800">Shipped</span>
                @elseif($order->status === 'delivered')
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-950/80 text-emerald-400 border border-emerald-800">Delivered</span>
                @endif
            </div>
        </div>

        <!-- Total price paid -->
        <div class="flex justify-between items-center px-2">
            <span class="text-base font-bold text-white">Amount Paid</span>
            <span class="text-2xl font-black text-brand-400">${{ number_format($order->price, 2) }}</span>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-8 border-t border-slate-850">
            <a href="{{ route('books.index') }}" class="w-full sm:w-auto text-center px-6 py-3 border border-slate-700 hover:border-slate-500 text-xs font-bold text-slate-300 rounded-xl transition-all">
                Browse More Books
            </a>
            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto text-center px-6 py-3 bg-brand-600 hover:bg-brand-500 text-xs font-bold text-white rounded-xl shadow-lg transition-all">
                Go to My Purchases
            </a>
        </div>
    </div>
</div>
@endsection
