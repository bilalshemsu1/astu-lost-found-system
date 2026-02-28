<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\ClaimsController as AdminClaimsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ItemsController as AdminItemsController;
use App\Http\Controllers\Admin\MatchesController as AdminMatchesController;
use App\Http\Controllers\Admin\ReportsController as AdminReportsController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Student\ClaimsController as StudentClaimsController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ItemsController as StudentItemsController;
use App\Http\Controllers\Student\MatchesController as StudentMatchesController;
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

Route::middleware(['auth'])->group(function () {

    // dashboard route
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');

    // claim routes
    Route::get('/claim/create/{similarityLog}', [StudentClaimsController::class, 'create'])->name('student.claims.create');
    Route::post('/claim', [StudentClaimsController::class, 'store'])->name('student.claims.store');
    Route::get('/claims', [StudentClaimsController::class, 'index'])->name('student.claims');

    Route::get('/matches', [StudentMatchesController::class, 'index'])->name('student.matches');
    Route::post('/matches/{similarityLog}/dismiss', [StudentMatchesController::class, 'dismiss'])->name('student.matches.dismiss');
    Route::get('/my-items', [StudentItemsController::class, 'myItems'])->name('student.my-items');


    // items routes
    Route::prefix('/items')->group(function () {
        Route::get('/', [StudentItemsController::class, 'index'])->name('student.items');
        Route::get('lost', [StudentItemsController::class, 'showLostForm'])->name('student.lost');
        Route::get('found', [StudentItemsController::class, 'showFoundForm'])->name('student.found');

        Route::post('lost', [StudentItemsController::class, 'storeLost'])->name('student.lost.post');
        Route::post('found', [StudentItemsController::class, 'storeFound'])->name('student.found.post');

    });


    // admin routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/pending-items', [AdminItemsController::class, 'pending'])->name('admin.items.pending');
        Route::patch('/admin/items/{item}/approve', [AdminItemsController::class, 'approve'])->name('admin.items.approve');
        Route::patch('/admin/items/{item}/reject', [AdminItemsController::class, 'reject'])->name('admin.items.reject');
        Route::get('/admin/items/found/create', [AdminItemsController::class, 'createFound'])->name('admin.items.found.create');
        Route::post('/admin/items/found', [AdminItemsController::class, 'storeFound'])->name('admin.items.found.store');
        Route::get('/admin/items', [AdminItemsController::class, 'index'])->name('admin.items');
        Route::get('/admin/matches', [AdminMatchesController::class, 'index'])->name('admin.matches');
        Route::get('/admin/claims', [AdminClaimsController::class, 'index'])->name('admin.claims');
        Route::get('/admin/claims/{claim}/review', [AdminClaimsController::class, 'review'])->name('admin.claims.review');
        Route::patch('/admin/claims/{claim}/approve', [AdminClaimsController::class, 'approve'])->name('admin.claims.approve');
        Route::patch('/admin/claims/{claim}/reject', [AdminClaimsController::class, 'reject'])->name('admin.claims.reject');
        Route::patch('/admin/claims/{claim}/handover-confirm', [AdminClaimsController::class, 'confirmHandover'])->name('admin.claims.handover.confirm');
        Route::get('/admin/users', [AdminUsersController::class, 'index'])->name('admin.users');
        Route::get('/admin/users/create', [AdminUsersController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [AdminUsersController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/reports', [AdminReportsController::class, 'index'])->name('admin.reports');
        Route::get('/admin/statistics', [AdminReportsController::class, 'statistics'])->name('admin.statistics');
    });
});
