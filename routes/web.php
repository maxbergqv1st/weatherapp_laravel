<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn() => Inertia::render('Landing'))->name('home');
Route::get('/weather', [WeatherController::class, 'show'])->name('weather.show');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/favorites/toggle', [WeatherController::class, 'toggleFavorite']);
    Route::get('/favorites', [WeatherController::class, 'favorites'])->name('weather.favorites');
});

require __DIR__.'/auth.php';