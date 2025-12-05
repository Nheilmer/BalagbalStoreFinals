<?php

use App\Models\Customer;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Category;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/debug', function () {
    $customers = Customer::all();
    return view('debug', compact('customers'));
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::get('/admin', [CustomerController::class, 'index'])->name('admin.dashboard');

Route::resource('customers', CustomerController::class);
Route::resource('products', ProductController::class);

// Protected User Dashboard

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // grab active products, with their category
        $products = Product::with('category')
            ->where('is_active', true)
            ->get();

        // optional: grab categories if you want dynamic category buttons
        $categories = Category::all();

        return view('userDashboard', compact('user', 'products', 'categories'));
    })->name('dashboard');
});

Route::put('/profile/update', [AuthController::class, 'updateProfile'])
    ->middleware('auth')
    ->name('profile.update');

Route::post('/orders/confirm', [\App\Http\Controllers\OrderController::class, 'confirm'])
    ->middleware('auth')
    ->name('orders.confirm');

Route::get('/customers/{id}/history', [CustomerController::class, 'history']);
