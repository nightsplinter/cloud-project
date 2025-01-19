<?php

use App\Http\Controllers\PantryController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('dashboard', [PantryController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('ingredient', [PantryController::class, 'add'])
    ->middleware(['auth', 'verified'])
    ->name('pantry.add');

Route::get('ingredient/{id}', [PantryController::class, 'edit'])
    ->middleware(middleware: ['auth', 'verified'])
    ->name('pantry.edit');

Route::view('profile', 'profile')
    ->middleware(middleware: ['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
