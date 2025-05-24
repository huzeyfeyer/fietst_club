<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::controller(NewsController::class)->group(function () {
    Route::get('/news', 'index')->name('news.index');
    Route::get('/news/create', 'create')->name('news.create')->middleware('auth');
    Route::post('/news', 'store')->name('news.store')->middleware('auth');
    Route::get('/news/{news}', 'show')->name('news.show');
    Route::get('/news/{news}/edit', 'edit')->name('news.edit')->middleware('auth');
    Route::put('/news/{news}', 'update')->name('news.update')->middleware('auth');
    Route::delete('/news/{news}', 'destroy')->name('news.destroy')->middleware('auth');
});

require __DIR__.'/auth.php';
