@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center space-x-2">
                <span class="w-2 h-8 bg-amber-500 rounded"></span>
                <span>Admin Dashboard</span>
            </h1>
            <p class="text-sm text-slate-400 mt-2">Manage your bookstore inventory, pricing, availability, and customer orders catalog.</p>
        </div>
        <div class="flex items-center space-x-3 w-full sm:w-auto">
            <a href="{{ route('admin.books.create') }}" class="w-full sm:w-auto text-center px-5 py-3 bg-brand-600 hover:bg-brand-500 text-xs font-bold text-white rounded-2xl shadow-lg transition-all">
                + Add New Book
            </a>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex border-b border-slate-850 mb-8 space-x-6 text-sm">
        <a href="?tab=books" class="pb-4 font-bold border-b-2 transition-all {{ (request('tab') != 'orders' && request('tab') != 'password') ? 'text-brand-400 border-brand-500' : 'text-slate-400 hover:text-white border-transparent' }}">
            <i class="fa-solid fa-book-open mr-1.5 text-brand-400"></i> Books Inventory ({{ $books->total() }})
        </a>
        <a href="?tab=orders" class="pb-4 font-bold border-b-2 transition-all {{ request('tab') == 'orders' ? 'text-brand-400 border-brand-500' : 'text-slate-400 hover:text-white border-transparent' }}">
            <i class="fa-solid fa-box-open mr-1.5 text-brand-400"></i> Customer Orders ({{ $orders->total() }})
        </a>
        <a href="?tab=password" class="pb-4 font-bold border-b-2 transition-all {{ request('tab') == 'password' ? 'text-brand-400 border-brand-500' : 'text-slate-400 hover:text-white border-transparent' }}">
            <i class="fa-solid fa-lock mr-1.5 text-brand-400"></i> Change Password
        </a>
    </div>

    @if(request('tab') == 'password')
        <!-- Password Form -->
        <div class="max-w-2xl mx-auto glass-panel p-8 sm:p-10 rounded-3xl relative overflow-hidden shadow-2xl">
            <h2 class="text-2xl font-extrabold text-white tracking-tight mb-6 flex items-center space-x-2">
                <span class="w-2 h-6 bg-rose-500 rounded"></span>
                <span>Change Administrator Password</span>
            </h2>

            <!-- Success/Errors -->
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

            <form action="{{ route('admin.password.update') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Current Password</label>
                    <input type="password" name="current_password" id="current_password" required class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm">
                </div>

                <!-- New Password -->
                <div>
                    <label for="new_password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">New Password</label>
                    <input type="password" name="new_password" id="new_password" required class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm">
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label for="new_password_confirmation" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" required class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm">
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end pt-4 border-t border-slate-800">
                    <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-500 text-xs font-bold text-white rounded-xl shadow-lg transition-all">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    @elseif(request('tab') == 'orders')
        <!-- Orders Table -->
        <div class="glass-panel rounded-3xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-900/50 border-b border-slate-800 text-slate-400 font-bold">
                            <th class="p-6">Order ID</th>
                            <th class="p-6">Client Name</th>
                            <th class="p-6">Book Title</th>
                            <th class="p-6">Price</th>
                            <th class="p-6">Shipping Destination</th>
                            <th class="p-6">Purchased On</th>
                            <th class="p-6">Status</th>
                            <th class="p-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800 text-slate-300">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-900/20 transition-colors">
                                <td class="p-6 font-bold text-white whitespace-nowrap">
                                    #{{ $order->id }}
                                </td>
                                <td class="p-6">
                                    <div class="font-bold text-white">{{ $order->client_name }}</div>
                                    <div class="text-xs text-slate-400">User: {{ $order->user ? $order->user->name : 'Guest' }} ({{ $order->user ? $order->user->email : '' }})</div>
                                </td>
                                <td class="p-6">
                                    @if($order->book)
                                        <span class="font-bold text-white">{{ $order->book->title }}</span>
                                        <div class="text-xs text-slate-400">by {{ $order->book->author }}</div>
                                    @else
                                        <span class="text-slate-500">[Deleted Book]</span>
                                    @endif
                                </td>
                                <td class="p-6 whitespace-nowrap font-bold text-brand-400">
                                    ${{ number_format($order->price, 2) }}
                                </td>
                                <td class="p-6 font-medium text-slate-300">
                                    <div class="text-xs text-slate-200">{{ $order->address }}</div>
                                    <div class="text-xs text-slate-400">{{ $order->city }}, {{ $order->state }} {{ $order->zip }}</div>
                                    <div class="text-[10px] text-slate-500">Phone: {{ $order->phone }}</div>
                                </td>
                                <td class="p-6 whitespace-nowrap text-slate-400">
                                    {{ $order->created_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="p-6 whitespace-nowrap">
                                    @if($order->status === 'pending')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-800 text-slate-300 border border-slate-700">Pending</span>
                                    @elseif($order->status === 'confirmed')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-950/80 text-blue-450 border border-blue-800">Confirmed</span>
                                    @elseif($order->status === 'shipped')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-950/80 text-amber-450 border border-amber-800">Shipped</span>
                                    @elseif($order->status === 'delivered')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-950/80 text-emerald-400 border border-emerald-800">Delivered</span>
                                    @endif
                                </td>
                                <td class="p-6 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end space-x-3">
                                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="text-xs font-bold text-brand-400 hover:text-brand-300 transition-colors">
                                            Edit
                                        </a>
                                        <a href="{{ route('admin.orders.destroy.get', $order->id) }}" onclick="return confirm('Are you sure you want to cancel and delete this order?');" class="text-xs font-bold text-rose-400 hover:text-rose-300 transition-colors">
                                            Cancel & Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-slate-400">
                                    No customer orders placed yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="p-6 border-t border-slate-800 bg-slate-900/10">
                    {{ $orders->appends(['tab' => 'orders'])->links() }}
                </div>
            @endif
        </div>
    @else
        <!-- Inventory Table -->
        <div class="glass-panel rounded-3xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-900/50 border-b border-slate-800 text-slate-400 font-bold">
                            <th class="p-6">Cover</th>
                            <th class="p-6">Title / Author</th>
                            <th class="p-6">Category</th>
                            <th class="p-6">Price</th>
                            <th class="p-6">Status</th>
                            <th class="p-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800 text-slate-300">
                        @forelse($books as $book)
                            <tr class="hover:bg-slate-900/20 transition-colors">
                                <!-- Cover -->
                                <td class="p-6 whitespace-nowrap">
                                    <div class="w-12 h-16 bg-slate-950 rounded overflow-hidden shadow">
                                        @if($book->image_url)
                                            <img src="{{ asset($book->image_url) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-[8px] text-slate-600 font-bold bg-slate-950">Cover</div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Title/Author -->
                                <td class="p-6">
                                    <div class="font-bold text-white line-clamp-1">{{ $book->title }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">by {{ $book->author }}</div>
                                </td>

                                <!-- Category -->
                                <td class="p-6 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-slate-900 border border-slate-800 text-slate-300">
                                        {{ $book->category ? $book->category->name : 'Uncategorized' }}
                                    </span>
                                </td>

                                <!-- Price -->
                                <td class="p-6 whitespace-nowrap font-bold text-white">
                                    ${{ number_format($book->price, 2) }}
                                </td>

                                <!-- Status -->
                                <td class="p-6 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $book->availability ? 'bg-emerald-950/80 text-emerald-400 border border-emerald-800' : 'bg-rose-950/80 text-rose-400 border border-rose-800' }}">
                                        {{ $book->availability ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="p-6 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end space-x-3">
                                        <a href="{{ route('admin.books.edit', $book->id) }}" class="text-xs font-bold text-brand-400 hover:text-brand-300 transition-colors">
                                            Edit
                                        </a>
                                        <a href="{{ route('admin.books.destroy.get', $book->id) }}" onclick="return confirm('Are you sure you want to delete this book?');" class="text-xs font-bold text-rose-400 hover:text-rose-300 transition-colors">
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-slate-400">
                                    No books found in local database. Click "+ Add New Book" to begin.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($books->hasPages())
                <div class="p-6 border-t border-slate-800 bg-slate-900/10">
                    {{ $books->appends(['tab' => 'books'])->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
