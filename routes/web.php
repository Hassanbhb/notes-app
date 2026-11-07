<?php

use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\NoteController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/notes');

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
});

Route::middleware('auth')->group(function () {
    Route::resource('notes', NoteController::class)->only(['index', 'store']);
    Route::post('logout', LogoutController::class)->name('logout');
});
