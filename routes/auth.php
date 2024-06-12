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

use App\Http\Controllers\ScenarioOneController;
use App\Http\Controllers\ScenarioOneAllController;
use App\Http\Controllers\EmployeeDetailController;

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
    // Route::resource('products', ProductController::class);

    //scenario 1 first xml generation
    Route::get('scenario-one-list', [ScenarioOneController::class, 'list'])->name('scenario-one-list');
    Route::post('generate-xml', [ScenarioOneController::class, 'generate_xml'])->name('generate-xml');
    Route::get('fetch-last-detail', [ScenarioOneController::class,'fetchLastDetail'])->name('fetch-last-detail');

    //scenario 1 old xml generation
    Route::get('scenario-one-all-list', [ScenarioOneAllController::class, 'list'])->name('scenario-one-all-list');
    Route::get('/edit-scenario-one-all/{id}', [ScenarioOneAllController::class, 'edit'])->name('edit-scenario-one-all');
    Route::put('save-scenario-one-all', [ScenarioOneAllController::class, 'update'])->name('save-scenario-one-all');
    Route::get('changestatus-scenario-one-all/{id}', [ScenarioOneAllController::class, 'activation'])->name('changestatus-scenario-one-all');
    Route::get('blockscenarioone/{id}', [ScenarioOneAllController::class, 'block'])->name('blockscenarioone');
    Route::post('generate-old-xml', [ScenarioOneAllController::class, 'generate_xml'])->name('generate-old-xml');

    Route::get('employee', [EmployeeDetailController::class, 'index'])->name('employee');
    Route::put('save-employee-details', [EmployeeDetailController::class, 'update'])->name('save-employee-details');


});
