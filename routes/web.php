<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[HomeController::class,'index'])->name('home');



Route::group(['account'],function(){
    //Guest route
Route::group(['middleware'=>'guest'],function(){
    Route::get('/register',[AccountController::class,'registration'])->name('account.register');
    Route::get('/login',[AccountController::class,'login'])->name('account.login');
    Route::post('t/proccessregister', [AccountController::class, 'processRegistration'])->name('account.processRegistration');
    Route::post('/authenticate', [AccountController::class, 'authenticate'])->name('account.authenticate');
});
    //Authenticat route
    Route::group(['middleware'=>'auth'],function(){
        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::put('/update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::post('/update-profile-pic', [AccountController::class, 'updateProfilePic'])->name('account.updateProfilePic');
        Route::get('/create-job', [AccountController::class, 'createJob'])->name('account.createJob');
        Route::post('/save-job', [AccountController::class, 'saveJob'])->name('account.saveJob');
        Route::get('/my-jobs', [AccountController::class, 'myJobs'])->name('account.myJobs');
        Route::get('/my-jobs/edit/{jobId}', [AccountController::class, 'editJob'])->name('account.editJob');
        Route::post('/update-job/{jobId}', [AccountController::class, 'updateJob'])->name('account.updateJob');
        Route::post('/delete-job', [AccountController::class, 'deleteJob'])->name('account.deleteJob');
    });
});