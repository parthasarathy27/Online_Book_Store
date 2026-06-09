@extends('layouts.app')

@section('title', 'Browse Books')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center space-x-2">
            <span class="w-2 h-8 bg-brand-500 rounded"></span>
            <span>Browse Catalog</span>
        </h1>
        <p class="text-sm text-slate-400 mt-2">Discover premium books curated for tech, business, and creative minds.</p>
    </div>

    <!-- Filters Panel -->
    <form action="{{ route('books.index') }}" method="GET" class="glass-panel p-6 rounded-3xl mb-8 flex flex-col lg:flex-row items-center justify-between gap-4">
        <!-- Search -->
        <div class="w-full lg:w-1/3 relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or author..." class="w-full bg-slate-900 border border-slate-800 rounded-2xl px-4 py-3 text-sm text-white placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
        </div>

        <!-- Category -->
        <div class="w-full lg:w-1/4">
            <select name="category" onchange="this.form.submit()" class="w-full bg-slate-900 border border-slate-800 rounded-2xl px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Sort -->
        <div class="w-full lg:w-1/4">
            <select name="sort" onchange="this.form.submit()" class="w-full bg-slate-900 border border-slate-800 rounded-2xl px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Releases</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Title: A to Z</option>
            </select>
        </div>

        <!-- Submit & Clear Button -->
        <div class="w-full lg:w-auto flex items-center gap-2">
            <button type="submit" class="w-full lg:w-auto btn-gradient text-white text-sm font-semibold px-6 py-3 rounded-2xl shadow-lg">
                Apply Filters
            </button>
            @if(request()->anyFilled(['search', 'category', 'sort']))
                <a href="{{ route('books.index') }}" class="w-full lg:w-auto text-center px-6 py-3 border border-slate-700 hover:border-slate-500 rounded-2xl text-sm font-semibold text-slate-300 transition-colors">
                    Clear
                </a>
            @endif
        </div>
    </form>

    <!-- Books Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($books as $book)
            <div class="glass-panel rounded-2xl overflow-hidden hover:shadow-brand-500/5 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 flex flex-col h-full">
                <!-- Cover Image -->
                <div class="relative pt-[120%] bg-slate-900 overflow-hidden group">
                    @if($book->image_url)
                        <img src="{{ asset($book->image_url) }}" alt="{{ $book->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center bg-slate-900 text-slate-600 font-bold">
                            No Cover
                        </div>
                    @endif

                    <!-- Stock Status Badge -->
                    <div class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider shadow {{ $book->availability ? 'bg-emerald-950/80 text-emerald-400 border border-emerald-800' : 'bg-rose-950/80 text-rose-400 border border-rose-800' }}">
                        {{ $book->availability ? 'In Stock' : 'Out of Stock' }}
                    </div>

                    <!-- Price Badge -->
                    <div class="absolute top-3 right-3 bg-brand-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow">
                        ${{ number_format($book->price, 2) }}
                    </div>
                </div>

                <!-- Content -->
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
            <div class="col-span-full text-center py-24 glass-panel rounded-3xl">
                <svg class="mx-auto h-12 w-12 text-slate-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-white">No books found</h3>
                <p class="mt-1 text-sm text-slate-400">Try adjusting your filters or search query.</p>
                <div class="mt-6">
                    <a href="{{ route('books.index') }}" class="btn-gradient text-white text-xs font-semibold px-4 py-2 rounded-xl">
                        Reset Filters
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $books->links() }}
    </div>
</div>
@endsection
