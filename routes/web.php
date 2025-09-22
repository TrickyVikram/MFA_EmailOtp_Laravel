<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/otp', [App\Http\Controllers\Auth\OTPController::class, 'index'])->name('otp.verify');
Route::post('/otp', [App\Http\Controllers\Auth\OTPController::class, 'verify'])->name('otp.post');


Route::get('/2fa', [App\Http\Controllers\Auth\TwoFAController::class, 'index'])->name('2fa.verify');
Route::post('/2fa', [App\Http\Controllers\Auth\TwoFAController::class, 'verify'])->name('2fa.post');

Route::middleware(['auth'])->group(function () {
    Route::get('/2fa/setup', [App\Http\Controllers\Auth\TwoFAController::class, 'setup'])->name('2fa.setup');
    Route::post('/2fa/setup', [App\Http\Controllers\Auth\TwoFAController::class, 'setupPost'])->name('2fa.setup.post');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/2fa/disable', [App\Http\Controllers\ProfileController::class, 'disable2fa'])->name('2fa.disable');
});


Route::group(['middleware' => ['auth', '2fa']], function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');

});
