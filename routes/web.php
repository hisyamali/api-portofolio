<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/profiles/get-data', [ProfileController::class, 'getProfile']);

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('/profiles')->group(function () {
        Route::get('/', [ProfileController::class, 'index']);
        Route::post('/', [ProfileController::class, 'store']);
        Route::post('/update-profile/{profile_id}', [ProfileController::class, 'updateProfile']);
        Route::get('/edit-password', [ProfileController::class, 'editPassword'])->name('profile.edit');
        Route::patch('/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update');
        Route::delete('/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';
