<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ItemController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function (){
    Route::get('/student', function () {
        return view('student.dashboard');
    })->name('student.dashboard');

    Route::get('/claim', function () {
        return view('student.claims.index');
    })->name('student.claims.show');
    // Route::prefix('items/claims')->group(function () {
    //     Route::get('/', [ClaimsController::class, 'index'])->name('student.items.claims.index');
    //     Route::get('/{claim}', [ClaimsController::class, 'show'])->name('student.items.claims.show');
    // });

    Route::middleware(['admin'])->group(function (){
        Route::get('/admin', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });
});