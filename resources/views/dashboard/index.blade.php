@extends('layouts.app')

@section('title', 'My Purchases')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center space-x-2">
            <span class="w-2 h-8 bg-brand-600 rounded"></span>
            <span>My Purchases</span>
        </h1>
        <p class="text-sm text-slate-400 mt-2">View details of your purchased books, download keys/receipts, and track shipping address details.</p>
    </div>

    <!-- Session Alerts -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-950/40 border border-emerald-800/50 text-emerald-400 text-xs flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-950/40 border border-rose-800/50 text-rose-400 text-xs flex items-center space-x-2">
            <i class="fa-solid fa-circle-exclamation text-sm"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Purchases Table / Cards -->
    <div class="glass-panel rounded-3xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-900/50 border-b border-slate-800 text-slate-400 font-bold">
                        <th class="p-6">Cover</th>
                        <th class="p-6">Book Details</th>
                        <th class="p-6">Price Paid</th>
                        <th class="p-6">Purchased On</th>
                        <th class="p-6">Client / Shipping Address</th>
                        <th class="p-6 text-right">Status / Receipt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-slate-300">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-900/10 transition-colors">
                            <!-- Cover -->
                            <td class="p-6 whitespace-nowrap">
                                <div class="w-12 h-16 bg-slate-950 rounded overflow-hidden shadow">
                                    @if($order->book && $order->book->image_url)
                                        <img src="{{ asset($order->book->image_url) }}" alt="{{ $order->book->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-[8px] text-slate-600 font-bold bg-slate-950">Cover</div>
                                    @endif
                                </div>
                            </td>

                            <!-- Book Details -->
                            <td class="p-6">
                                @if($order->book)
                                    <a href="{{ route('books.show', $order->book->id) }}" class="font-bold text-white hover:text-brand-400 transition-colors line-clamp-1">
                                        {{ $order->book->title }}
                                    </a>
                                    <div class="text-xs text-slate-400 mt-0.5">by {{ $order->book->author }}</div>
                                @else
                                    <div class="font-bold text-slate-500 line-clamp-1">[Deleted Book]</div>
                                @endif
                            </td>

                            <!-- Price -->
                            <td class="p-6 whitespace-nowrap font-bold text-white">
                                ${{ number_format($order->price, 2) }}
                            </td>

                            <!-- Date -->
                            <td class="p-6 whitespace-nowrap text-slate-400">
                                {{ $order->created_at->format('M d, Y h:i A') }}
                            </td>

                            <!-- Shipping Address -->
                            <td class="p-6 text-slate-300">
                                <div class="font-semibold text-white text-xs">{{ $order->client_name }}</div>
                                <div class="text-xs text-slate-400 mt-1">
                                    {{ $order->address }}, {{ $order->city }}, {{ $order->state }} {{ $order->zip }}
                                </div>
                                <div class="text-[10px] text-slate-500 mt-0.5">Phone: {{ $order->phone }}</div>
                            </td>

                            <!-- Status / Receipt -->
                            <td class="p-6 text-right whitespace-nowrap">
                                @if($order->status === 'pending')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-800 text-slate-400 border border-slate-700">
                                        Pending Confirmation
                                    </span>
                                @else
                                    <div class="flex flex-col items-end space-y-1.5">
                                        @if($order->status === 'confirmed')
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-blue-950/80 text-blue-400 border border-blue-900">Confirmed</span>
                                        @elseif($order->status === 'shipped')
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-amber-950/80 text-amber-400 border border-amber-900">Shipped</span>
                                        @elseif($order->status === 'delivered')
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-emerald-950/80 text-emerald-400 border border-emerald-900">Delivered</span>
                                        @endif
                                        <a href="{{ route('books.order_confirmation', $order->id) }}" class="text-xs font-bold text-brand-400 hover:text-brand-300 transition-colors">
                                            View Receipt
                                        </a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-400">
                                You haven't purchased any books yet. 
                                <a href="{{ route('books.index') }}" class="text-brand-400 hover:text-brand-300 font-bold underline ml-1">Browse Books</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-6 border-t border-slate-800 bg-slate-900/10">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
