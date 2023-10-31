<?php


use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', App\Livewire\Dashboard::class)->name('dashboard');
Route::post('/logout', App\Livewire\Dashboard::class)->name('logout');
Route::get('/login', App\Livewire\Auth\Login::class)->name('login');
