<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MatchingController;
use App\Http\Controllers\MessagingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/v1/login', [AuthController::class, 'login']);
Route::post('/v1/logout', [AuthController::class, 'logout']);

Route::get('/register', [AuthenticationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthenticationController::class, 'register']);

Route::middleware(['auth'])->group(function () {
    // Matching Routes
    Route::get('/matching', [MatchingController::class, 'index'])->name('matching.index');
    Route::post('/matching/store', [MatchingController::class, 'store'])->name('matching.store');
    
    // Messaging Routes
    Route::get('/messaging', [MessagingController::class, 'index'])->name('messaging.index');
    Route::post('/messaging/send', [MessagingController::class, 'sendMessage'])->name('messaging.send');

    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/edit-profile', [DashboardController::class, 'editProfile'])->name('dashboard.edit-profile');
    Route::post('/dashboard/update-profile', [DashboardController::class, 'updateProfile'])->name('dashboard.update-profile');

    // Feedback Routes
    Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback/store', [FeedbackController::class, 'store'])->name('feedback.store');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/users', [AdminController::class, 'manageUsers'])->name('users.index');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::get('/feedback', [AdminController::class, 'manageFeedback'])->name('feedback.index');
        Route::delete('/feedback/{id}', [AdminController::class, 'deleteFeedback'])->name('feedback.delete');
    });
});