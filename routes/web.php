<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\MedicineManagement;
use App\Livewire\SalesManagement;
use App\Livewire\Categories;


Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Admin Dashboard
Route::get('/dashboard', function () {
    return view('dashboard'); // Ensure this points to the correct admin dashboard view
})->middleware(['auth', 'verified', 'role-manager:admin'])->name('dashboard');

// Admin Routes
Route::get('/medicine-management', MedicineManagement::class)
    ->middleware(['auth', 'role-manager:admin'])
    ->name('medicine.management.list');
Route::get('/categories', Categories::class)
    ->middleware(['auth', 'role-manager:admin'])
    ->name('category.list');

// Sales Routes for Cashier/Seller
Route::get('/sales-management', SalesManagement::class)
    ->middleware(['auth', 'role-manager:cashier'])
    ->name('sales-management');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';



Route::get('/medicine-management', MedicineManagement::class)->name('medicine.list');
Route::get('/sales-management', SalesManagement::class)->name('sales.list');
Route::get('/categories', Categories::class)->name('category.list');
Route::get('/sales-management', SalesManagement::class)->name('sales-management');
Route::get('/medicine-management', MedicineManagement::class)->name('medicine.management.list');

//Route::get('/dashboard', SalesManagement::class)->name('dashboard');

