<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ItemController;
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
    Route::get('/student/dashboard', [ItemController::class, 'dashboard'])->name('student.dashboard');

    // claim routes
    Route::get('/claim/create/{similarityLog}', [ClaimController::class, 'create'])->name('student.claims.create');
    Route::post('/claim', [ClaimController::class, 'store'])->name('student.claims.store');
    Route::get('/claims', [ClaimController::class, 'index'])->name('student.claims');

    Route::get('/matches', [ItemController::class, 'matches'])->name('student.matches');
    Route::post('/matches/{similarityLog}/dismiss', [ItemController::class, 'dismissMatch'])->name('student.matches.dismiss');
    Route::get('/my-items', [ItemController::class, 'myItems'])->name('student.my-items');


    // items routes
    Route::prefix('/items')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('student.items');
        Route::get('lost', [ItemController::class, 'showLostForm'])->name('student.lost');
        Route::get('found', [ItemController::class, 'showFoundForm'])->name('student.found');

        Route::post('lost', [ItemController::class, 'postLostItem'])->name('student.lost.post');
        Route::post('found', [ItemController::class, 'postFoundItem'])->name('student.found.post');

    });


    // admin routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/pending-items', [AdminController::class, 'pendingItems'])->name('admin.items.pending');
        Route::patch('/admin/items/{item}/approve', [AdminController::class, 'approveItem'])->name('admin.items.approve');
        Route::patch('/admin/items/{item}/reject', [AdminController::class, 'rejectItem'])->name('admin.items.reject');
        Route::get('/admin/items', [AdminController::class, 'items'])->name('admin.items');
        Route::get('/admin/matches', [AdminController::class, 'matches'])->name('admin.matches');
        Route::get('/admin/claims', [AdminController::class, 'claims'])->name('admin.claims');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
        Route::get('/admin/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');
    });
});

// Test route for matching
Route::get('/test-match', function (\App\Services\ItemMatcher $matcher) {
    // Get one lost item to tes
    $lost = \App\Models\Item::where('type', 'lost')->first();

    if (!$lost) {
        return 'Need at least one lost item!';
    }

    // Test matching
    $matches = $matcher->findMatches($lost);
    $topMatch = $matches[0]['candidate']->title ?? null;

    return [
        'lost_item' => $lost->title,
        'top_match_item' => $topMatch,
        'matches_found' => count($matches),
        'matches' => $matches
    ];

});
