<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\NewsCategoryController as AdminNewsCategoryController;
use App\Http\Controllers\Admin\FaqCategoryController as AdminFaqCategoryController;
use App\Http\Controllers\Admin\FaqItemController as AdminFaqItemController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ContactController;
use App\Models\News;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $latestNews = News::with('user')
                        ->latest()
                        ->take(3)
                        ->get();
    return view('home', ['latestNews' => $latestNews]);
})->name('home');

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

// Beheer van gebruikers door admin
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    // Later: Route::resource('users', AdminUserController::class); als alle resource methods gebruikt worden.

    // Beheer van nieuwscategorieën door admin
    Route::resource('news-categories', AdminNewsCategoryController::class)->except(['show']); // show is niet nodig voor categorie beheer

    // Beheer van FAQ categorieën door admin
    Route::resource('faq-categories', AdminFaqCategoryController::class)->except(['show']);

    // Beheer van FAQ items door admin
    Route::resource('faq-items', AdminFaqItemController::class)->except(['show']);

    // Beheer van contactberichten door admin
    Route::resource('contact-messages', AdminContactMessageController::class)->except(['create', 'store', 'edit', 'update']);
    Route::patch('contact-messages/{contactMessage}/mark-as-unread', [AdminContactMessageController::class, 'markAsUnread'])->name('contact-messages.markAsUnread');
    Route::patch('contact-messages/{contactMessage}/archive', [AdminContactMessageController::class, 'archive'])->name('contact-messages.archive');
    Route::patch('contact-messages/{contactMessage}/unarchive', [AdminContactMessageController::class, 'unarchive'])->name('contact-messages.unarchive');

    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

// Publieke profielpagina
Route::get('/users/{user:name}', [UserProfileController::class, 'show'])->name('users.profile.show');

// Publieke FAQ pagina
Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

// Contactformulier
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

require __DIR__.'/auth.php';
