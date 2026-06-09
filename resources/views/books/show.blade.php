@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Back Navigation -->
    <div class="mb-8">
        <a href="{{ route('books.index') }}" class="text-sm font-semibold text-brand-400 hover:text-brand-300 transition-colors flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Back to Catalog</span>
        </a>
    </div>

    <!-- Product Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
        <!-- Left: Image Section -->
        <div class="lg:col-span-5 flex justify-center">
            <div class="w-full max-w-sm glass-panel p-4 rounded-3xl relative overflow-hidden">
                <div class="aspect-[3/4] bg-slate-900 rounded-2xl overflow-hidden relative shadow-2xl">
                    @if($book->image_url)
                        <img src="{{ asset($book->image_url) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-slate-900 text-slate-600 font-bold">
                            No Cover
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Text Info Section -->
        <div class="lg:col-span-7 flex flex-col justify-center">
            <!-- Category and Availability -->
            <div class="flex items-center space-x-3 mb-4">
                <span class="text-sm text-brand-400 font-semibold uppercase tracking-wider">
                    {{ $book->category ? $book->category->name : 'Uncategorized' }}
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $book->availability ? 'bg-emerald-950/60 text-emerald-400 border-emerald-800/60' : 'bg-rose-950/60 text-rose-400 border-rose-800/60' }}">
                    {{ $book->availability ? 'Available' : 'Out of Stock' }}
                </span>
            </div>

            <!-- Title & Author -->
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-2">
                {{ $book->title }}
            </h1>
            <p class="text-lg text-slate-300 font-medium mb-6">
                by <span class="text-white">{{ $book->author }}</span>
            </p>

            <hr class="border-slate-800 mb-6">

            <!-- Pricing Box -->
            <div class="glass-panel p-6 rounded-2xl mb-8 flex items-center justify-between">
                <div>
                    <span class="text-xs text-slate-400 block uppercase font-bold tracking-wider mb-1">Price</span>
                    <span class="text-3xl font-extrabold text-white">${{ number_format($book->price, 2) }}</span>
                </div>
                <div>
                    @if($book->availability)
                        <a href="{{ route('books.checkout', $book->id) }}" class="btn-gradient text-white text-sm font-semibold px-8 py-3.5 rounded-xl shadow-lg inline-block text-center hover:opacity-90 transition-opacity">
                            Buy Now
                        </a>
                    @else
                        <button class="bg-slate-800 text-slate-500 text-sm font-semibold px-8 py-3.5 rounded-xl cursor-not-allowed" disabled>
                            Unavailable
                        </button>
                    @endif
                </div>
            </div>

            <!-- Description -->
            <div class="mb-8">
                <h3 class="text-sm font-extrabold text-white uppercase tracking-wider mb-3">Book Description</h3>
                <p class="text-slate-300 text-sm leading-relaxed whitespace-pre-line">
                    {{ $book->description ?: 'No description available for this book.' }}
                </p>
            </div>

            <!-- Google Books API Integration -->
            @if(isset($googleBook))
                <div class="mt-8 pt-8 border-t border-slate-800">
                    <h3 class="text-xs font-extrabold text-brand-400 uppercase tracking-wider mb-4 flex items-center space-x-1.5">
                        <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Google Books API Integration</span>
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 bg-slate-900/40 border border-slate-800/80 p-4 rounded-xl">
                        <div>
                            <span class="text-[10px] text-slate-500 uppercase font-bold block mb-0.5">Publisher</span>
                            <span class="text-xs font-semibold text-slate-200">{{ $googleBook['publisher'] ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-500 uppercase font-bold block mb-0.5">Published Date</span>
                            <span class="text-xs font-semibold text-slate-200">{{ $googleBook['publishedDate'] ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-500 uppercase font-bold block mb-0.5">Page Count</span>
                            <span class="text-xs font-semibold text-slate-200">{{ $googleBook['pageCount'] ?? 'N/A' }} pages</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-500 uppercase font-bold block mb-0.5">Average Rating</span>
                            <span class="text-xs font-semibold text-amber-400 flex items-center space-x-0.5">
                                <span>★</span>
                                <span>{{ $googleBook['averageRating'] ?? 'N/A' }} ({{ $googleBook['ratingsCount'] ?? 0 }})</span>
                            </span>
                        </div>
                    </div>
                    @if(isset($googleBook['previewLink']))
                        <div class="mt-3 text-right">
                            <a href="{{ $googleBook['previewLink'] }}" target="_blank" class="text-xs font-medium text-brand-400 hover:text-brand-300 transition duration-200 inline-flex items-center space-x-1">
                                <span>Preview on Google Books &rarr;</span>
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Related Books -->
    @if(count($relatedBooks) > 0)
        <div class="mt-20">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center space-x-2">
                <span class="w-1.5 h-6 bg-brand-500 rounded"></span>
                <span>You May Also Like</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($relatedBooks as $related)
                    <a href="{{ route('books.show', $related->id) }}" class="glass-panel p-4 rounded-2xl flex items-center space-x-4 hover:bg-slate-900/40 transition-all duration-300 hover:-translate-y-1 block">
                        <div class="w-16 h-20 bg-slate-900 rounded overflow-hidden flex-shrink-0">
                            @if($related->image_url)
                                <img src="{{ asset($related->image_url) }}" alt="{{ $related->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[8px] text-slate-600 font-bold bg-slate-900">Cover</div>
                            @endif
                        </div>
                        <div class="flex-grow min-w-0">
                            <h3 class="font-bold text-white text-sm truncate mb-0.5">{{ $related->title }}</h3>
                            <p class="text-xs text-slate-400 truncate mb-1">by {{ $related->author }}</p>
                            <span class="text-brand-400 text-xs font-bold">${{ number_format($related->price, 2) }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
