<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/alert', function () {
    return view('admin.alert');
})->name('admin.alert');

Route::get('/admin/analysis', function () {
    return view('admin.analysis');
})->name('admin.analysis');

Route::get('/admin/mitigation', function () {
    return view('admin.mitigation');
})->name('admin.mitigation');

Route::get('/admin/settings', function () {
    return view('admin.settings');
})->name('admin.settings');
