<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login/owner', [AuthController::class, 'showOwnerLogin'])->name('login.owner');
    Route::get('/login/manager', [AuthController::class, 'showManagerLogin'])->name('login.manager');
    Route::get('/login/chef', [AuthController::class, 'showChefLogin'])->name('login.chef');
    Route::get('/login/waiter', [AuthController::class, 'showWaiterLogin'])->name('login.waiter');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/login/pin', [AuthController::class, 'showPinLogin'])->name('login.pin');
    Route::post('/login/pin', [AuthController::class, 'pinLogin'])->name('login.pin.submit');
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Profile management
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password');
    Route::post('/change-pin', [AuthController::class, 'changePin'])->name('change-pin');
    
    // Notifications
    Route::get('/notifications', function () {
        return view('main.notifications.index');
    })->name('notifications.index');
    
    // Dashboard routes with role-based access
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Owner routes
    Route::middleware('role:owner')->prefix('owner')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'owner'])->name('owner.dashboard');
    });
    
    // Admin routes
    Route::middleware('role:admin,owner')->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    });
    
    // Kasir routes
    Route::middleware('permission:access_pos')->prefix('pos')->group(function () {
        Route::get('/', function () { return view('pos.index'); })->name('pos.index');
    });
    
    // Waiter routes
    Route::middleware('permission:manage_tables')->prefix('waiter')->group(function () {
        Route::get('/tables', function () { return view('waiter.tables'); })->name('waiter.tables');
    });
    
    // Kitchen routes
    Route::middleware('permission:view_kitchen_display')->prefix('kitchen')->group(function () {
        Route::get('/orders', function () { return view('kitchen.orders'); })->name('kitchen.orders');
    });
    
    // Inventory routes
    Route::middleware('permission:manage_inventory')->prefix('inventory')->group(function () {
        Route::get('/', function () { return view('inventory.index'); })->name('inventory.index');
    });
    
    // Supervisor routes
    Route::middleware('role:supervisor,admin,owner')->prefix('supervisor')->group(function () {
        Route::get('/dashboard', function () { return view('supervisor.dashboard'); })->name('supervisor.dashboard');
    });
});
