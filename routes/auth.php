<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Adminpanel\UserController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

use App\Http\Controllers\LoginController;

//backend 
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');

Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
    ->middleware('auth')
    ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
    ->middleware('auth');

Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::put('update-role', [RoleController::class, 'update'])->name('update-role');
    Route::resource('users', UserController::class);
    Route::get('users-list',[UserController::class,'list'])->name('users-list');
    Route::put('save-user', [UserController::class, 'update'])->name('save-user');
    Route::get('changestatus-user/{id}', [UserController::class, 'activation'])->name('changestatus-user');
    Route::get('blockuser/{id}', [UserController::class, 'block'])->name('blockuser');
    Route::post('checkEmailAvailability', [UserController::class, 'checkEmailAvailability'])->name('checkEmailAvailability');
    Route::resource('products', ProductController::class);

    Route::get('complain-category', [ComplainCategoryController::class, 'index'])->name('complain-category');
    Route::post('new-category', [ComplainCategoryController::class, 'store'])->name('new-category');
    Route::get('complain-category-list', [ComplainCategoryController::class, 'list'])->name('complain-category-list');
    Route::get('/edit-category/{id}', [ComplainCategoryController::class, 'edit'])->name('edit-category');
    Route::put('save-complain-category', [ComplainCategoryController::class, 'update'])->name('save-complain-category');
    Route::get('/status-category/{id}', [ComplainCategoryController::class, 'activation'])->name('status-category');

});
