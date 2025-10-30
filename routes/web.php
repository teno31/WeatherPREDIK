<?php

use App\Livewire\App\Dashboard;
use App\Livewire\Auth\SignIn;
use App\Livewire\Auth\SignUp;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\GetStarted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class)->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/asdasd', GetStarted::class)->name('get.started');
    Route::get('/api/users/sign-in', SignIn::class)->name('sign.in');
    Route::get('/api/users/sign-up', SignUp::class)->name('sign.up');
    
});


Route::middleware('auth')->group(function() {
    Route::get('/email/verify', VerifyEmail::class)->name('verification.notice');
    Route::get('/api/weathers/app', Dashboard::class)->name('dashboard');
});
