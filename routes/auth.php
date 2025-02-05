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

use App\Http\Controllers\EmployeeDetailController;

use App\Http\Controllers\ScenarioOneController;
use App\Http\Controllers\ScenarioOneAllController;
use App\Http\Controllers\ScenarioTwoController;
use App\Http\Controllers\ScenarioTwoAllController;
use App\Http\Controllers\ScenarioThreeController;
use App\Http\Controllers\ScenarioThreeAllController;
use App\Http\Controllers\ScenarioFourController;
use App\Http\Controllers\ScenarioFourAllController;
use App\Http\Controllers\ScenarioFiveController;
use App\Http\Controllers\ScenarioFiveAllController;
use App\Http\Controllers\ScenarioSixController;
use App\Http\Controllers\ScenarioSixAllController;
use App\Http\Controllers\ScenarioSevenController;
use App\Http\Controllers\ScenarioSevenAllController;
use App\Http\Controllers\ScenarioEightController;
use App\Http\Controllers\ScenarioEightAllController;
use App\Http\Controllers\DatabaseUpdateController;


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

Route::get('/update-database', [DatabaseUpdateController::class, 'index']);

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

    //Employee - Master
    Route::get('employee', [EmployeeDetailController::class, 'index'])->name('employee');
    Route::put('save-employee-details', [EmployeeDetailController::class, 'update'])->name('save-employee-details');

    //scenario 1 first xml generation
    Route::get('scenario-one-list', [ScenarioOneController::class, 'list'])->name('scenario-one-list');
    Route::post('generate-xml', [ScenarioOneController::class, 'generate_xml'])->name('generate-xml');
    Route::get('fetch-last-detail', [ScenarioOneController::class,'fetchLastDetail'])->name('fetch-last-detail');

    //scenario 1 old xml generation
    Route::get('scenario-one-all-list', [ScenarioOneAllController::class, 'list'])->name('scenario-one-all-list');
    Route::get('/scenario-one-edit/{id}', [ScenarioOneAllController::class, 'datalist'])->name('scenario-one-edit');
    Route::get('blockscenarioonexml/{id}', [ScenarioOneAllController::class, 'blockXML'])->name('blockscenarioonexml');
    Route::get('changestatus-scenario-one-all-xml/{id}', [ScenarioOneAllController::class, 'activationXml'])->name('changestatus-scenario-one-all-xml');
    Route::get('/download-excel-one/{id}', [ScenarioOneAllController::class, 'downloadExcel'])->name('download-excel-one');
    Route::get('/edit-scenario-one-all/{id}', [ScenarioOneAllController::class, 'edit'])->name('edit-scenario-one-all');
    Route::put('save-scenario-one-all', [ScenarioOneAllController::class, 'update'])->name('save-scenario-one-all');
    Route::get('scenario-one-edit/changestatus-scenario-one-all/{id}', [ScenarioOneAllController::class, 'activation'])->name('changestatus-scenario-one-all');
    Route::get('scenario-one-edit/blockscenarioone/{id}', [ScenarioOneAllController::class, 'block'])->name('blockscenarioone');
    Route::post('generate-old-xml', [ScenarioOneAllController::class, 'generate_xml'])->name('generate-old-xml');

    //scenario 2 first xml generation
    Route::get('scenario-two-list', [ScenarioTwoController::class, 'list'])->name('scenario-two-list');
    Route::post('generate-xml-two', [ScenarioTwoController::class, 'generate_xml'])->name('generate-xml-two');
    Route::get('fetch-last-detail-two', [ScenarioTwoController::class,'fetchLastDetail'])->name('fetch-last-detail-two');

    //scenario 2 old xml generation
    Route::get('scenario-two-all-list', [ScenarioTwoAllController::class, 'list'])->name('scenario-two-all-list');
    Route::get('/scenario-two-edit/{id}', [ScenarioTwoAllController::class, 'datalist'])->name('scenario-two-edit');
    Route::get('blockscenariotwoxml/{id}', [ScenarioTwoAllController::class, 'blockXML'])->name('blockscenariotwoxml');
    Route::get('changestatus-scenario-two-all-xml/{id}', [ScenarioTwoAllController::class, 'activationXml'])->name('changestatus-scenario-two-all-xml');
    Route::get('/download-excel-two/{id}', [ScenarioTwoAllController::class, 'downloadExcel'])->name('download-excel-two');
    Route::get('/edit-scenario-two-all/{id}', [ScenarioTwoAllController::class, 'edit'])->name('edit-scenario-two-all');
    Route::put('save-scenario-two-all', [ScenarioTwoAllController::class, 'update'])->name('save-scenario-two-all');
    Route::get('scenario-two-edit/changestatus-scenario-two-all/{id}', [ScenarioTwoAllController::class, 'activation'])->name('changestatus-scenario-two-all');
    Route::get('blockscenariotwo/{id}', [ScenarioTwoAllController::class, 'block'])->name('blockscenariotwo');
    Route::post('generate-old-xml-two', [ScenarioTwoAllController::class, 'generate_xml'])->name('generate-old-xml-two');

    //scenario 3 first xml generation
    Route::get('scenario-three-list', [ScenarioThreeController::class, 'list'])->name('scenario-three-list');
    Route::post('generate-xml-three', [ScenarioThreeController::class, 'generate_xml'])->name('generate-xml-three');
    Route::get('fetch-last-detail-three', [ScenarioThreeController::class,'fetchLastDetail'])->name('fetch-last-detail-three');

    //scenario 3 old xml generation
    Route::get('scenario-three-all-list', [ScenarioThreeAllController::class, 'list'])->name('scenario-three-all-list');
    Route::get('/scenario-three-edit/{id}', [ScenarioThreeAllController::class, 'datalist'])->name('scenario-three-edit');
    Route::get('blockscenariothreexml/{id}', [ScenarioThreeAllController::class, 'blockXML'])->name('blockscenariothreexml');
    Route::get('changestatus-scenario-three-all-xml/{id}', [ScenarioThreeAllController::class, 'activationXml'])->name('changestatus-scenario-three-all-xml');
    Route::get('/download-excel-three/{id}', [ScenarioThreeAllController::class, 'downloadExcel'])->name('download-excel-three');
    Route::get('/edit-scenario-three-all/{id}', [ScenarioThreeAllController::class, 'edit'])->name('edit-scenario-three-all');
    Route::put('save-scenario-three-all', [ScenarioThreeAllController::class, 'update'])->name('save-scenario-three-all');
    Route::get('scenario-three-edit/changestatus-scenario-three-all/{id}', [ScenarioThreeAllController::class, 'activation'])->name('changestatus-scenario-three-all');
    Route::get('blockscenariothree/{id}', [ScenarioThreeAllController::class, 'block'])->name('blockscenariothree');
    Route::post('generate-old-xml-three', [ScenarioThreeAllController::class, 'generate_xml'])->name('generate-old-xml-three');

    //scenario 4 first xml generation
    Route::get('scenario-four-list', [ScenarioFourController::class, 'list'])->name('scenario-four-list');
    Route::post('generate-xml-four', [ScenarioFourController::class, 'generate_xml'])->name('generate-xml-four');
    Route::get('fetch-last-detail-four', [ScenarioFourController::class,'fetchLastDetail'])->name('fetch-last-detail-four');

    //scenario 4 old xml generation
    Route::get('scenario-four-all-list', [ScenarioFourAllController::class, 'list'])->name('scenario-four-all-list');
    Route::get('/scenario-four-edit/{id}', [ScenarioFourAllController::class, 'datalist'])->name('scenario-four-edit');
    Route::get('blockscenariofourxml/{id}', [ScenarioFourAllController::class, 'blockXML'])->name('blockscenariofourxml');
    Route::get('changestatus-scenario-four-all-xml/{id}', [ScenarioFourAllController::class, 'activationXml'])->name('changestatus-scenario-four-all-xml');
    Route::get('/download-excel-four/{id}', [ScenarioFourAllController::class, 'downloadExcel'])->name('download-excel-four');
    Route::get('/edit-scenario-four-all/{id}', [ScenarioFourAllController::class, 'edit'])->name('edit-scenario-four-all');
    Route::put('save-scenario-four-all', [ScenarioFourAllController::class, 'update'])->name('save-scenario-four-all');
    Route::get('scenario-four-edit/changestatus-scenario-four-all/{id}', [ScenarioFourAllController::class, 'activation'])->name('changestatus-scenario-four-all');
    Route::get('blockscenariofour/{id}', [ScenarioFourAllController::class, 'block'])->name('blockscenariofour');
    Route::post('generate-old-xml-four', [ScenarioFourAllController::class, 'generate_xml'])->name('generate-old-xml-four');

    //scenario 5 first xml generation
    Route::get('scenario-five-list', [ScenarioFiveController::class, 'list'])->name('scenario-five-list');
    Route::post('generate-xml-five', [ScenarioFiveController::class, 'generate_xml'])->name('generate-xml-five');
    Route::get('fetch-last-detail-five', [ScenarioFiveController::class,'fetchLastDetail'])->name('fetch-last-detail-five');

    //scenario 5 old xml generation
    Route::get('scenario-five-all-list', [ScenarioFiveAllController::class, 'list'])->name('scenario-five-all-list');
    Route::get('/scenario-five-edit/{id}', [ScenarioFiveAllController::class, 'datalist'])->name('scenario-five-edit');
    Route::get('blockscenariofivexml/{id}', [ScenarioFiveAllController::class, 'blockXML'])->name('blockscenariofivexml');
    Route::get('changestatus-scenario-five-all-xml/{id}', [ScenarioFiveAllController::class, 'activationXml'])->name('changestatus-scenario-five-all-xml');
    Route::get('/download-excel-five/{id}', [ScenarioFiveAllController::class, 'downloadExcel'])->name('download-excel-five');
    Route::get('/edit-scenario-five-all/{id}', [ScenarioFiveAllController::class, 'edit'])->name('edit-scenario-five-all');
    Route::put('save-scenario-five-all', [ScenarioFiveAllController::class, 'update'])->name('save-scenario-five-all');
    Route::get('scenario-five-edit/changestatus-scenario-five-all/{id}', [ScenarioFiveAllController::class, 'activation'])->name('changestatus-scenario-five-all');
    Route::get('blockscenariofive/{id}', [ScenarioFiveAllController::class, 'block'])->name('blockscenariofive');
    Route::post('generate-old-xml-five', [ScenarioFiveAllController::class, 'generate_xml'])->name('generate-old-xml-five');

    //scenario 6 first xml generation
    Route::get('scenario-six-list', [ScenarioSixController::class, 'list'])->name('scenario-six-list');
    Route::post('generate-xml-six', [ScenarioSixController::class, 'generate_xml'])->name('generate-xml-six');
    Route::get('fetch-last-detail-six', [ScenarioSixController::class,'fetchLastDetail'])->name('fetch-last-detail-six');

    //scenario 6 old xml generation
    Route::get('scenario-six-all-list', [ScenarioSixAllController::class, 'list'])->name('scenario-six-all-list');
    Route::get('/scenario-six-edit/{id}', [ScenarioSixAllController::class, 'datalist'])->name('scenario-six-edit');
    Route::get('blockscenariosixxml/{id}', [ScenarioSixAllController::class, 'blockXML'])->name('blockscenariosixxml');
    Route::get('changestatus-scenario-six-all-xml/{id}', [ScenarioSixAllController::class, 'activationXml'])->name('changestatus-scenario-six-all-xml');
    Route::get('/download-excel-six/{id}', [ScenarioSixAllController::class, 'downloadExcel'])->name('download-excel-six');
    Route::get('/edit-scenario-six-all/{id}', [ScenarioSixAllController::class, 'edit'])->name('edit-scenario-six-all');
    Route::put('save-scenario-six-all', [ScenarioSixAllController::class, 'update'])->name('save-scenario-six-all');
    Route::get('scenario-six-edit/changestatus-scenario-six-all/{id}', [ScenarioSixAllController::class, 'activation'])->name('changestatus-scenario-six-all');
    Route::get('blockscenariosix/{id}', [ScenarioSixAllController::class, 'block'])->name('blockscenariosix');
    Route::post('generate-old-xml-six', [ScenarioSixAllController::class, 'generate_xml'])->name('generate-old-xml-six');

    //scenario 7 first xml generation
    Route::get('scenario-seven-list', [ScenarioSevenController::class, 'list'])->name('scenario-seven-list');
    Route::post('generate-xml-seven', [ScenarioSevenController::class, 'generate_xml'])->name('generate-xml-seven');
    Route::get('fetch-last-detail-seven', [ScenarioSevenController::class,'fetchLastDetail'])->name('fetch-last-detail-seven');

    //scenario 7 old xml generation
    Route::get('scenario-seven-all-list', [ScenarioSevenAllController::class, 'list'])->name('scenario-seven-all-list');
    Route::get('/scenario-seven-edit/{id}', [ScenarioSevenAllController::class, 'datalist'])->name('scenario-seven-edit');
    Route::get('blockscenariosevenxml/{id}', [ScenarioSevenAllController::class, 'blockXML'])->name('blockscenariosevenxml');
    Route::get('changestatus-scenario-seven-all-xml/{id}', [ScenarioSevenAllController::class, 'activationXml'])->name('changestatus-scenario-seven-all-xml');
    Route::get('/download-excel-seven/{id}', [ScenarioSevenAllController::class, 'downloadExcel'])->name('download-excel-seven');
    Route::get('/edit-scenario-seven-all/{id}', [ScenarioSevenAllController::class, 'edit'])->name('edit-scenario-seven-all');
    Route::put('save-scenario-seven-all', [ScenarioSevenAllController::class, 'update'])->name('save-scenario-seven-all');
    Route::get('scenario-seven-edit/changestatus-scenario-seven-all/{id}', [ScenarioSevenAllController::class, 'activation'])->name('changestatus-scenario-seven-all');
    Route::get('blockscenarioseven/{id}', [ScenarioSevenAllController::class, 'block'])->name('blockscenarioseven');
    Route::post('generate-old-xml-seven', [ScenarioSevenAllController::class, 'generate_xml'])->name('generate-old-xml-seven');

    //scenario 8 first xml generation
    Route::get('scenario-eight-list', [ScenarioEightController::class, 'list'])->name('scenario-eight-list');
    Route::post('generate-xml-eight', [ScenarioEightController::class, 'generate_xml'])->name('generate-xml-eight');
    Route::get('fetch-last-detail-eight', [ScenarioEightController::class,'fetchLastDetail'])->name('fetch-last-detail-eight');

    //scenario 8 old xml generation
    Route::get('scenario-eight-all-list', [ScenarioEightAllController::class, 'list'])->name('scenario-eight-all-list');
    Route::get('/scenario-eight-edit/{id}', [ScenarioEightAllController::class, 'datalist'])->name('scenario-eight-edit');
    Route::get('blockscenarioeightxml/{id}', [ScenarioEightAllController::class, 'blockXML'])->name('blockscenarioeightxml');
    Route::get('changestatus-scenario-eight-all-xml/{id}', [ScenarioEightAllController::class, 'activationXml'])->name('changestatus-scenario-eight-all-xml');
    Route::get('/download-excel-eight/{id}', [ScenarioEightAllController::class, 'downloadExcel'])->name('download-excel-eight');
    Route::get('/edit-scenario-eight-all/{id}', [ScenarioEightAllController::class, 'edit'])->name('edit-scenario-eight-all');
    Route::put('save-scenario-eight-all', [ScenarioEightAllController::class, 'update'])->name('save-scenario-eight-all');
    Route::get('scenario-eight-edit/changestatus-scenario-eight-all/{id}', [ScenarioEightAllController::class, 'activation'])->name('changestatus-scenario-eight-all');
    Route::get('blockscenarioeight/{id}', [ScenarioEightAllController::class, 'block'])->name('blockscenarioeight');
    Route::post('generate-old-xml-eight', [ScenarioEightAllController::class, 'generate_xml'])->name('generate-old-xml-eight');

});
