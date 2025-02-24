<?php

use App\Http\Controllers\PantryController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\AnalystController;
use App\Http\Middleware\EnsureUserIsAnalyst;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('imprint', 'imprint')
    ->name('imprint');

Route::view('privacypolicy', 'privacy-policy')
    ->name('privacypolicy');

Route::get('dashboard', [PantryController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('ingredient', [PantryController::class, 'add'])
    ->middleware(['auth', 'verified'])
    ->name('pantry.add');

Route::get('ingredient/{id}', [PantryController::class, 'edit'])
    ->middleware(middleware: ['auth', 'verified'])
    ->name('pantry.edit');

Route::get('recipe', [RecipeController::class, 'add'])
    ->middleware(middleware: ['auth', 'verified'])
    ->name('recipe.add');

Route::get('recipe/detail/{id}', [RecipeController::class, 'detail'])
    ->middleware(middleware: ['auth', 'verified'])
    ->name('recipe.show');

Route::get('recipe/{id}', [RecipeController::class, 'edit'])
    ->middleware(middleware: ['auth', 'verified'])
    ->name('recipe.edit');

Route::get('recipeFinder', [RecipeController::class, 'finder'])
    ->middleware(middleware: ['auth', 'verified'])
    ->name('recipe.finder');

Route::view('profile', 'profile')
    ->middleware(middleware: ['auth'])
    ->name('profile');

Route::get('analysis', [AnalystController::class, 'index'])
    ->middleware(middleware: ['auth', 'verified', EnsureUserIsAnalyst::class])
    ->name('analysis');

Route::post('analysis', [AnalystController::class, 'runCommand'])
    ->middleware(middleware: ['auth', 'verified', EnsureUserIsAnalyst::class])
    ->name('analysis.runCommand');

require __DIR__ . '/auth.php';
