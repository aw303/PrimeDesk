<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')
        ->name('dashboard');

    Route::view('profile', 'profile')
        ->name('profile');

    Volt::route('admin/roles', 'pages.admin.roles')
        ->middleware('role:Admin')
        ->name('admin.roles');
});

require __DIR__.'/auth.php';
