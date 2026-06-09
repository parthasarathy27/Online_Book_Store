@extends('layouts.app')

@section('title', 'Add New Book')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Back Navigation -->
    <div class="mb-8">
        <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-slate-400 hover:text-white transition-colors flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Back to Dashboard</span>
        </a>
    </div>

    <!-- Form Panel -->
    <div class="glass-panel p-8 sm:p-10 rounded-3xl relative overflow-hidden shadow-2xl">
        <div class="absolute inset-0 overflow-hidden pointer-events-none -z-10">
            <div class="absolute -right-10 -top-10 w-48 h-48 bg-brand-500/10 rounded-full blur-[40px]"></div>
        </div>

        <h1 class="text-2xl font-extrabold text-white tracking-tight mb-8">
            Add New Book
        </h1>

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

        <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Book Title</label>
                    <input type="text" name="title" id="title" required value="{{ old('title', request('title')) }}" class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm">
                </div>

                <!-- Author -->
                <div>
                    <label for="author" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Author Name</label>
                    <input type="text" name="author" id="author" required value="{{ old('author', request('author')) }}" class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm">
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Category</label>
                    <select name="category_id" id="category_id" class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Price ($)</label>
                    <input type="number" step="0.01" name="price" id="price" required value="{{ old('price') }}" class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm" placeholder="0.00">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Description</label>
                <textarea name="description" id="description" rows="5" class="w-full px-4 py-3 border border-slate-800 bg-slate-900 text-white rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 text-sm">{{ old('description', request('description')) }}</textarea>
            </div>

            <hr class="border-slate-800 my-8">

            <div>
                <!-- Cover Image Upload -->
                <div>
                    <label for="image" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Upload Cover Image</label>
                    <input type="file" name="image" id="image" class="w-full text-sm text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-300 hover:file:bg-slate-700">
                    <p class="text-[10px] text-slate-500 mt-1">Accepts PNG, JPG, JPEG, WEBP. Max 2MB.</p>
                </div>
            </div>

            <!-- Availability -->
            <div class="flex items-center mt-4">
                <input id="availability" name="availability" type="checkbox" value="1" {{ old('availability', 1) ? 'checked' : '' }} class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-slate-800 rounded bg-slate-900">
                <label for="availability" class="ml-2 block text-sm font-semibold text-slate-300">
                    Mark as Available / In Stock
                </label>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-800">
                <a href="{{ route('admin.dashboard') }}" class="px-5 py-3 border border-slate-700 hover:border-slate-500 text-xs font-bold text-slate-300 rounded-xl transition-all">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-500 text-xs font-bold text-white rounded-xl shadow-lg transition-all">
                    Save Book
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
