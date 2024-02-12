<?php

//use App\Models\Province;
use GuzzleHttp\Middleware;
//use App\Models\EstablishmentType;
//use App\Models\RegisterCoomplaint;
use Illuminate\Support\Facades\App;
//use App\Models\LabourOfficeDivision;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Adminpanel\LogActivityController;
use App\Http\Controllers\Adminpanel\DashboardController;
use App\Models\LabourOfficeDivision;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\TransactionDetailController;
use App\Http\Controllers\GeneratexmlController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/admin', function () {
//     return view('auth.login');
//     //return view('welcome');
// });

Route::get('/', function () {
    return view('auth.login');
    //return view('welcome');
});

Route::get('/login', function () {
    //dd('Hiiii');
   // rename('index.html', 'index15-03-2022.html');
   return view('auth.login');
    //return view('welcome');
});

Route::get('/main-dashboard', [DashboardController::class, 'mainDashboard'])->name('main-dashboard');

Route::get('new-register', [NewComplaintController::class, 'index'])->name('new-register');
Route::post('new-complaint', [NewComplaintController::class, 'store'])->name('new-complaint');
Route::get('complaint-tracking', [NewComplaintController::class, 'complainttracking'])->name('complaint-tracking');
Route::get('/search-complaint-reference', [NewComplaintController::class, 'searchcomplaint'])->name('search-complaint-reference');
Route::get('/verification', [NewComplaintController::class, 'verification'])->name('verification');
Route::get('/complaint-status', [NewComplaintController::class, 'complaintstatus'])->name('complaint-status');
Route::get('resent-otp/{id}', [NewComplaintController::class, 'resentotp'])->name('resent-otp');
Route::get('getCityFront', [NewComplaintController::class, 'getCityFront'])->name('getCityFront');

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);

Route::group(['middleware' => 'auth'], function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // });

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');   
   
    Route::view('profile', 'profile')->name('profile');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::get('log-activity-list', [LogActivityController::class, 'list'])->name('log-activity-list');
    Route::get('blocklog/{id}', [LogActivityController::class, 'block'])->name('blocklog');
    // Route::get('search-log', [LogActivityController::class, 'searchLog'])->name('search-log');

    Route::get('transaction-list', [TransactionDetailController::class, 'list'])->name('transaction-list');

    Route::get('generate-xml-list', [GeneratexmlController::class, 'list'])->name('generate-xml-list');
    Route::get('gen-xml', [GeneratexmlController::class,'index'])->name('gen-xml');

});

require __DIR__ . '/auth.php';
