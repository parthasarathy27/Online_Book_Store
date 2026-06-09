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

    }
}
