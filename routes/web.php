<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ItemController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

//auth routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function (){

    // dashboard route
    Route::get('/student/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');

    // claim routes
    Route::get('/claim', function () {
        return view('student.claims.index');
    })->name('student.claims.show');
    
    // items routes
    Route::prefix('/items')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('student.items.index');
        Route::get('lost', [ItemController::class, 'showLostForm'])->name('student.lost');
        Route::get('found', [ItemController::class, 'showFoundForm'])->name('student.found');

        Route::post('lost', [ItemController::class, 'postLostItem'])->name('student.lost.post');
        Route::post('found', [ItemController::class, 'postFoundItem'])->name('student.found.post');

    });


    // admin routes
    Route::middleware(['admin'])->group(function (){
        Route::get('/admin', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });
});