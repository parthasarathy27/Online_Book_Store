<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed Users
        User::updateOrCreate(
            ['email' => 'user@bookstore.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('user123'),
                'is_admin' => false,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@bookstore.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );

        // Seed Categories
        $programming = Category::updateOrCreate(['slug' => 'programming'], ['name' => 'Programming']);
        $business = Category::updateOrCreate(['slug' => 'business'], ['name' => 'Business']);
        $scifi = Category::updateOrCreate(['slug' => 'sci-fi'], ['name' => 'Science Fiction']);
        $selfhelp = Category::updateOrCreate(['slug' => 'self-help'], ['name' => 'Self Help']);

        // Seed Books
        Book::updateOrCreate(
            ['title' => 'Clean Code: A Handbook of Agile Software Craftsmanship'],
            [
                'category_id' => $programming->id,
                'author' => 'Robert C. Martin',
                'description' => 'Even bad code can function. But if code isn\'t clean, it can bring a development organization to its knees. Every year, countless hours and significant resources are lost because of poorly written code. But it doesn\'t have to be that way.',
                'price' => 34.99,
                'availability' => true,
                'image_url' => 'images/covers/clean_code.png'
            ]
        );

        Book::updateOrCreate(
            ['title' => 'Cosmic Odyssey: Journey to the Edge of the Universe'],
            [
                'category_id' => $scifi->id,
                'author' => 'Dr. Elena Rostova',
                'description' => 'An extraordinary journey through space and time, exploring distant galaxies, black holes, and the fundamental mysteries of the cosmos. Beautifully written and packed with the latest scientific discoveries.',
                'price' => 24.99,
                'availability' => true,
                'image_url' => 'images/covers/cosmic_odyssey.png'
            ]
        );

        Book::updateOrCreate(
            ['title' => 'Mastering Habits: Small Changes, Remarkable Results'],
            [
                'category_id' => $selfhelp->id,
                'author' => 'James Clearfield',
                'description' => 'Learn the scientific framework for building good habits and breaking bad ones. Discover how tiny daily changes can compound into massive personal and professional transformations over time.',
                'price' => 18.99,
                'availability' => true,
                'image_url' => 'images/covers/mastering_habits.png'
            ]
        );

        Book::updateOrCreate(
            ['title' => 'The Wealth Mindset: Overcoming Financial Roadblocks'],
            [
                'category_id' => $business->id,
                'author' => 'Sarah Jenkins',
                'description' => 'A comprehensive guide to changing your relationship with money, developing smart investment habits, and building long-term generational wealth. Real-world case studies and actionable advice.',
                'price' => 22.50,
                'availability' => true,
                'image_url' => 'images/covers/wealth_mindset.png'
            ]
        );

    }
}
