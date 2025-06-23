<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CasoController;
use App\Http\Controllers\Web\CaseController;
use App\Http\Controllers\Web\LegalCaseController;
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

// Rutas para el nuevo módulo de casos legales
Route::resource('legal-cases', LegalCaseController::class)
    ->names('legal-cases')
    ->middleware(['auth', 'verified']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('legal-cases/generate-description', [App\Http\Controllers\Web\LegalCaseController::class, 'generateDescription'])->name('legal-cases.generate-description');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para el Módulo de Documentos
    Route::resource('documents', App\Http\Controllers\Web\DocumentController::class);
    Route::post('documents/generate', [App\Http\Controllers\Web\DocumentController::class, 'generate'])->name('documents.generate');
    Route::get('ai/models/{provider}', [App\Http\Controllers\Web\DocumentController::class, 'getModels'])->name('ai.models');
    Route::get('documents/{document}/download', [App\Http\Controllers\Web\DocumentController::class, 'download'])->name('documents.download');
});

require __DIR__.'/auth.php';
