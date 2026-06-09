@extends('layouts.app')

@section('title', 'Admin - Edit Order Details')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center space-x-2">
                <span class="w-2 h-8 bg-amber-500 rounded"></span>
                <span>Edit Customer Order</span>
            </h1>
            <p class="text-sm text-slate-400 mt-2">Modify order details for Order #ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}.</p>
        </div>
        <a href="{{ route('admin.dashboard', ['tab' => 'orders']) }}" class="text-xs font-bold text-slate-400 hover:text-white transition-colors">
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

        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Order Account Details -->
            <div class="bg-slate-900/50 p-4 rounded-2xl border border-slate-850 mb-6 flex items-center justify-between text-xs text-slate-400">
                <div>
                    <span class="font-bold text-slate-500 uppercase block mb-1">Purchased By</span>
                    <span class="text-white font-semibold">{{ $order->user ? $order->user->name : 'Guest' }}</span>
                    <span class="text-slate-400">({{ $order->user ? $order->user->email : 'No email' }})</span>
                </div>
                <div>
                    <span class="font-bold text-slate-500 uppercase block mb-1">Book Title</span>
                    <span class="text-white font-semibold">{{ $order->book ? $order->book->title : '[Deleted]' }}</span>
                </div>
                <div>
                    <span class="font-bold text-slate-500 uppercase block mb-1">Price Paid</span>
                    <span class="text-brand-400 font-bold">${{ number_format($order->price, 2) }}</span>
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

            <!-- Order Status -->
            <div>
                <label for="status" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Order Status</label>
                <select name="status" id="status" required class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm">
                    <option value="pending" {{ old('status', $order->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ old('status', $order->status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="shipped" {{ old('status', $order->status) === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ old('status', $order->status) === 'delivered' ? 'selected' : '' }}>Delivered</option>
                </select>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-800">
                <a href="{{ route('admin.dashboard', ['tab' => 'orders']) }}" class="px-5 py-3 border border-slate-700 hover:border-slate-500 text-xs font-bold text-slate-300 rounded-xl transition-all">
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
