<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CasoController;
use App\Http\Controllers\Web\CaseController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas para casos (versión web)
Route::resource('casos', CasoController::class)->middleware(['auth', 'verified']);

// Rutas para la gestión de casos
Route::resource('cases', \App\Http\Controllers\Web\CaseController::class)
    ->names('cases')
    ->middleware(['auth', 'verified']);

// Rutas para la gestión de clientes
Route::resource('clients', \App\Http\Controllers\Web\ClientController::class)
    ->names('clients')
    ->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
