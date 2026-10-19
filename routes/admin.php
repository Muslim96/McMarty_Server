<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\FineReasonController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('restaurants', RestaurantController::class)
        ->except('show')->middleware('can:restaurants.manage');

    Route::resource('employers', EmployerController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])->middleware('can:employers.manage');
    Route::resource('employers', EmployerController::class)
        ->only(['index', 'show'])->middleware('can:employers.view');

    Route::get('fines', [FineController::class, 'index'])->name('fines.index')->middleware('can:fines.view');
    Route::middleware('can:fines.create')->group(function () {
        Route::get('fines/create', [FineController::class, 'create'])->name('fines.create');
        Route::post('fines', [FineController::class, 'store'])->name('fines.store');
    });
    Route::patch('fines/{fine}/pay', [FineController::class, 'pay'])->name('fines.pay')->middleware('can:fines.pay');
    Route::middleware('can:fines.manage')->group(function () {
        Route::patch('fines/{fine}/cancel', [FineController::class, 'cancel'])->name('fines.cancel');
        Route::get('fines/{fine}/edit', [FineController::class, 'edit'])->name('fines.edit');
        Route::put('fines/{fine}', [FineController::class, 'update'])->name('fines.update');
        Route::delete('fines/{fine}', [FineController::class, 'destroy'])->name('fines.destroy');
    });

    Route::resource('fine-reasons', FineReasonController::class)
        ->except('show')->middleware('can:fine-reasons.manage');

    Route::resource('users', UserController::class)
        ->except('show')->middleware('can:users.manage');
});
