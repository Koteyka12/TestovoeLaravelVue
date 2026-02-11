<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\YandexOrganizationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('yandex.settings');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('yandex.settings');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Яндекс Карты - Отзывы
    Route::get('/yandex/reviews', [ReviewController::class, 'index'])->name('yandex.reviews');
    Route::post('/yandex/reviews/sync', [ReviewController::class, 'sync'])->name('yandex.reviews.sync');

    // Яндекс Карты - Настройки
    Route::get('/yandex/settings', [YandexOrganizationController::class, 'settings'])->name('yandex.settings');
    Route::post('/yandex/settings', [YandexOrganizationController::class, 'store'])->name('yandex.settings.store');
    Route::delete('/yandex/organization/{organization}', [YandexOrganizationController::class, 'destroy'])->name('yandex.organization.destroy');
});

require __DIR__.'/auth.php';
