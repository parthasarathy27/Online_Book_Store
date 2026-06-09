@extends('layouts.app')

@section('title', 'Welcome to BookStore')

@section('content')
<!-- Hero Section -->
<div class="relative py-20 lg:py-28 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="px-3 py-1 text-xs font-semibold text-brand-300 bg-brand-950/60 border border-brand-800/80 rounded-full uppercase tracking-wider inline-block mb-4">
            Curated Literary Experiences
        </span>
        <h1 class="text-4xl sm:text-6xl font-black tracking-tight text-white mb-6">
            Discover Your Next <br/>
            <span class="text-gradient">Literary Adventure</span>
        </h1>
        <p class="max-w-2xl mx-auto text-lg text-slate-300 mb-8 leading-relaxed">
            Welcome to the Online Book Store. Browse our collections, search our catalog, and view detailed information including live Google Books API metadata.
        </p>

        <!-- Search Bar -->
        <div class="max-w-xl mx-auto">
            <form action="{{ route('books.index') }}" method="GET" class="flex items-center p-2 rounded-2xl glass-panel shadow-2xl">
                <div class="flex-grow flex items-center pl-3">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" placeholder="Search by title, author, or keyword..." class="w-full bg-transparent border-0 focus:ring-0 text-white placeholder-slate-400 text-sm focus:outline-none pl-3" required>
                </div>
                <button type="submit" class="btn-gradient text-white text-sm font-semibold px-6 py-3 rounded-xl shadow-lg">
                    Search
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Categories Quick Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-2xl font-bold text-white mb-6 flex items-center space-x-2">
        <span class="w-1.5 h-6 bg-brand-500 rounded"></span>
        <span>Explore by Category</span>
    </h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($categories as $category)
            <a href="{{ route('books.index', ['category' => $category->slug]) }}" class="glass-panel p-6 rounded-2xl hover:bg-slate-900/60 transition-all duration-300 group hover:-translate-y-1 block">
                <h3 class="font-bold text-slate-100 group-hover:text-brand-400 transition-colors duration-200">{{ $category->name }}</h3>
                <p class="text-xs text-slate-400 mt-2">{{ $category->books_count }} Books Available</p>
            </a>
        @endforeach
    </div>
</div>

<!-- Featured Books Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-white flex items-center space-x-2">
            <span class="w-1.5 h-6 bg-brand-500 rounded"></span>
            <span>Featured Books</span>
        </h2>
        <a href="{{ route('books.index') }}" class="text-sm font-semibold text-brand-400 hover:text-brand-300 transition-colors duration-200 flex items-center space-x-1">
            <span>View All</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @forelse($featuredBooks as $book)
            <div class="glass-panel rounded-2xl overflow-hidden hover:shadow-brand-500/5 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 flex flex-col h-full">
                <!-- Cover Image -->
                <div class="relative pt-[130%] bg-slate-900 overflow-hidden group">
                    @if($book->image_url)
                        <img src="{{ asset($book->image_url) }}" alt="{{ $book->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center bg-slate-900 text-slate-600 font-bold">
                            No Cover
                        </div>
                    @endif
                    <div class="absolute top-3 right-3 bg-brand-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                        ${{ number_format($book->price, 2) }}
                    </div>
                </div>

                <!-- Info -->
                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-xs text-brand-400 font-semibold uppercase tracking-wider mb-1 block">
                        {{ $book->category ? $book->category->name : 'Uncategorized' }}
                    </span>
                    <h3 class="font-bold text-white text-base line-clamp-1 mb-1">
                        {{ $book->title }}
                    </h3>
                    <p class="text-xs text-slate-400 mb-4">
                        by {{ $book->author }}
                    </p>
                    <p class="text-xs text-slate-300 line-clamp-3 mb-6 flex-grow">
                        {{ $book->description }}
                    </p>
                    
                    <a href="{{ route('books.show', $book->id) }}" class="w-full text-center py-2.5 rounded-xl border border-slate-700 hover:border-brand-500 hover:bg-brand-500/10 text-xs font-semibold text-slate-200 hover:text-white transition-all duration-200">
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-slate-400">
                No featured books available at the moment.
            </div>
        @endforelse
    </div>
</div>
@endsection
