<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\RouletteController;
use App\Http\Controllers\UserController;

Route::middleware('auth')->group(function () {

    Route::post('/roulette/spin', [RouletteController::class, 'spin'])
        ->name('roulette.spin');
    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');
    Route::post('/roulette/reset', [RouletteController::class, 'reset'])
        ->name('roulette.reset');
});
require __DIR__ . '/auth.php';
