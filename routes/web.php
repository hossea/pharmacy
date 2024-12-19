<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\MedicineManagement;
use App\Livewire\SalesManagement;
use App\Livewire\Categories;



Route::get('/', function () {
    return view('welcome');
});
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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



