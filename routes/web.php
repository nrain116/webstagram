<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

/**
 * Home Route
 */
Route::get('/', [HomeController::class, 'index'])->name('welcome');

/**
 * Timeline (authenticated)
 */
Route::get('/timeline', [TimelineController::class, 'index'])->middleware('auth')->name('timeline');


/**
 * Authentication Routes
 */
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login'); 
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');  // Using controller for logout
Route::get('auth/github', [LoginController::class, 'redirectToGitHub'])->name('github.login');
Route::get('auth/github/callback', [LoginController::class, 'handleGitHubCallback']);

/**
 * User Routes
 */
Route::get('/users/search', [UserController::class, 'search'])->name('users.search')->middleware('auth');

/**
 * Password Reset Routes
 */
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

/**
 * Email Verification Routes
 */
Route::get('/email/verify', [VerifyEmailController::class, 'show'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/resend', [VerifyEmailController::class, 'resend'])->middleware('auth')->name('verification.send');

/**
 * Profile Routes
 */

Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
Route::patch('/profile/{user}', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');
Route::post('/profile/{user}/photo', [ProfileController::class, 'photo'])->middleware('auth')->name('profile.photo');
Route::post('/profile/{user}/follow', [ProfileController::class, 'follow'])->middleware('auth')->name('follow');
Route::delete('/profile/{user}/follow', [ProfileController::class, 'unfollow'])->middleware('auth')->name('unfollow');


/**
 * Posts (CRUD)
 */
Route::get('/posts/create', [PostController::class, 'create'])->middleware('auth', 'verified')->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->middleware('auth', 'verified')->name('posts.store');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('like');
    Route::delete('/posts/{post}/like', [PostController::class, 'unlike'])->name('unlike');
});
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');