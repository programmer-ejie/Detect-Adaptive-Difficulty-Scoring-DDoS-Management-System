<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('admin.dashboard')
        : view('index');
})->name('home');

Route::get('/login', function () {
    return auth()->check()
        ? redirect()->route('admin.dashboard')
        : view('index');
})->name('login');

Route::post('/login', [AuthController::class, 'store'])->name('login.perform');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/alert', function () {
            return view('admin.alert');
        })->name('alert');

        Route::get('/analysis', function () {
            return view('admin.analysis');
        })->name('analysis');

        Route::get('/mitigation', function () {
            return view('admin.mitigation');
        })->name('mitigation');

        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('settings');
    });
});
