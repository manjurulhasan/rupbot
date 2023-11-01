<?php


use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'home']);
Route::get('/login', App\Livewire\Auth\Login::class)->name('login');
Route::get('/logout', [\App\Http\Controllers\HomeController::class, 'doLogout'])->name('logout');
Route::group(['middleware'=> ['auth']], function () {
    Route::get('/dashboard', App\Livewire\Dashboard::class)->name('dashboard');
    Route::get('/logs', App\Livewire\Log\Logs::class)->name('logs');
    Route::get('/admin-emails', App\Livewire\Admin\EmailList::class)->name('emails');
    Route::post('/logout', App\Livewire\Dashboard::class)->name('logout');
    Route::get('/test', App\Livewire\Test::class)->name('test');
});
