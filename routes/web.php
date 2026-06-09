<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;

// Public Routes
Route::get('/', [BookController::class, 'home'])->name('home');
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');

// Public Guest Auth Routes
Route::middleware('guest')->group(function () {
    // Customers
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    // Admin Login (Hidden from public navigation links)
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
});

// Authenticated Customer & Admin Routes
Route::middleware('auth')->group(function () {
    // Common Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Customer Buy Routes
    Route::get('/books/{id}/buy', [BookController::class, 'checkoutForm'])->name('books.checkout');
    Route::post('/books/{id}/buy', [BookController::class, 'purchase'])->name('books.purchase');
    Route::get('/orders/{id}/confirmation', [BookController::class, 'orderConfirmation'])->name('books.order_confirmation');
    Route::get('/orders/{id}/edit', [BookController::class, 'editOrder'])->name('books.orders.edit');
    Route::put('/orders/{id}', [BookController::class, 'updateOrder'])->name('books.orders.update');

    // Customer Purchases Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Protected Routes (Requires auth AND admin middleware)
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Admin Book Management
        Route::get('/books/create', [AdminDashboardController::class, 'create'])->name('books.create');
        Route::post('/books', [AdminDashboardController::class, 'store'])->name('books.store');
        Route::get('/books/{id}/edit', [AdminDashboardController::class, 'edit'])->name('books.edit');
        Route::put('/books/{id}', [AdminDashboardController::class, 'update'])->name('books.update');
        Route::match(['delete', 'post'], '/books/{id}/delete', [AdminDashboardController::class, 'destroy'])->name('books.destroy');
        Route::get('/books/{id}/delete', [AdminDashboardController::class, 'destroy'])->name('books.destroy.get');
        
        // Admin Order Management
        Route::get('/orders/{id}/edit', [AdminDashboardController::class, 'editOrder'])->name('orders.edit');
        Route::put('/orders/{id}', [AdminDashboardController::class, 'updateOrder'])->name('orders.update');
        Route::match(['delete', 'post'], '/orders/{id}/delete', [AdminDashboardController::class, 'destroyOrder'])->name('orders.destroy');
        Route::get('/orders/{id}/delete', [AdminDashboardController::class, 'destroyOrder'])->name('orders.destroy.get');

        // Admin Password Management
        Route::post('/password', [AdminDashboardController::class, 'updatePassword'])->name('password.update');
    });
});
