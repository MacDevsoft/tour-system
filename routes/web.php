<?php

use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TourController;

Route::get('/tours', [TourController::class, 'index'])->middleware('auth');
Route::get('/tours/create', [TourController::class, 'create'])->middleware('auth');
Route::post('/tours', [TourController::class, 'store'])->middleware('auth');

Route::get('/tours/{tour}', [TourController::class, 'show'])->middleware('auth')->name('tours.show');
Route::get('/tours/{tour}/edit', [TourController::class, 'edit'])->middleware('auth')->name('tours.edit');
Route::match(['put','patch'], '/tours/{tour}', [TourController::class, 'update'])->middleware('auth')->name('tours.update');
Route::delete('/tours/{tour}', [TourController::class, 'destroy'])->middleware('auth')->name('tours.destroy');
Route::get('/tours/{tour}/toggle', [TourController::class, 'toggle'])->middleware('auth')->name('tours.toggle');



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';
