<?php


use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'home']);
Route::get('/login', App\Livewire\Auth\Login::class)->name('login');
Route::group(['middleware'=> ['auth']], function () {
    Route::get('/dashboard', App\Livewire\Dashboard::class)->name('dashboard');
    Route::post('/logout', App\Livewire\Dashboard::class)->name('logout');
    Route::get('/test', App\Livewire\Test::class)->name('test');
});
