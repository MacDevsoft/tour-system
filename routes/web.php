<?php

use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TourController;
use App\Http\Controllers\BankAccountController;

Route::get('/tours', [TourController::class, 'index'])->middleware('auth');
Route::get('/tours/create', [TourController::class, 'create'])->middleware('auth');
Route::post('/tours', [TourController::class, 'store'])->middleware('auth');

Route::get('/tours/{tour}', [TourController::class, 'show'])->middleware('auth')->name('tours.show');
Route::get('/tours/{tour}/edit', [TourController::class, 'edit'])->middleware('auth')->name('tours.edit');
Route::match(['put','patch'], '/tours/{tour}', [TourController::class, 'update'])->middleware('auth')->name('tours.update');
Route::delete('/tours/{tour}', [TourController::class, 'destroy'])->middleware('auth')->name('tours.destroy');
Route::get('/tours/{tour}/toggle', [TourController::class, 'toggle'])->middleware('auth')->name('tours.toggle');

// Cuentas bancarias (admin)
Route::middleware('auth')->group(function () {
    Route::get('/bank-accounts', [BankAccountController::class, 'index'])->name('bank_accounts.index');
    Route::get('/bank-accounts/create', [BankAccountController::class, 'create'])->name('bank_accounts.create');
    Route::post('/bank-accounts', [BankAccountController::class, 'store'])->name('bank_accounts.store');
    Route::get('/bank-accounts/{bank_account}/edit', [BankAccountController::class, 'edit'])->name('bank_accounts.edit');
    Route::patch('/bank-accounts/{bank_account}', [BankAccountController::class, 'update'])->name('bank_accounts.update');
    Route::delete('/bank-accounts/{bank_account}', [BankAccountController::class, 'destroy'])->name('bank_accounts.destroy');
    Route::get('/bank-accounts/{bank_account}/activate', [BankAccountController::class, 'activate'])->name('bank_accounts.activate');
    Route::get('/bank-accounts/{bank_account}/deactivate', [BankAccountController::class, 'deactivate'])->name('bank_accounts.deactivate');
});



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
