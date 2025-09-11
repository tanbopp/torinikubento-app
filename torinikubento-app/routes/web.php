<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManageAccountController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\OtherCostController;

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
    
    // Manage Accounts (User Management with advanced features)
    Route::middleware('permission:manage_users')->prefix('manage-accounts')->name('manage-accounts.')->group(function () {
        Route::get('/', [ManageAccountController::class, 'index'])->name('index');
        Route::get('/create', [ManageAccountController::class, 'create'])->name('create');
        Route::post('/', [ManageAccountController::class, 'store'])->name('store');
        Route::get('/{id}', [ManageAccountController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ManageAccountController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ManageAccountController::class, 'update'])->name('update');
        Route::post('/{id}/deactivate', [ManageAccountController::class, 'deactivate'])->name('deactivate');
        Route::post('/{id}/reactivate', [ManageAccountController::class, 'reactivate'])->name('reactivate');
        Route::post('/{id}/force-logout', [ManageAccountController::class, 'forceLogout'])->name('force-logout');
        Route::delete('/{id}', [ManageAccountController::class, 'destroy'])->name('destroy');
    });

    // Role Management
    Route::middleware('permission:manage_roles')->prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/{role}', [RoleController::class, 'show'])->name('show');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
    });
    
    // Owner & Admin Reports
    Route::middleware('permission:view_financial_reports')->prefix('reports')->group(function () {
        Route::get('/sales', function () { return view('reports.sales'); })->name('reports.sales');
        Route::get('/expenses', function () { return view('reports.expenses'); })->name('reports.expenses');
        Route::get('/profit-loss', function () { return view('reports.profit-loss'); })->name('reports.profit-loss');
        Route::get('/analytics', function () { return view('reports.analytics'); })->name('reports.analytics');
    });

    // Daily Reports for Admin
    Route::middleware('permission:view_daily_reports')->prefix('reports')->group(function () {
        Route::get('/daily-sales', function () { return view('reports.daily-sales'); })->name('reports.daily-sales');
        Route::get('/shifts', function () { return view('reports.shifts'); })->name('reports.shifts');
    });

    // User Management
    Route::middleware('permission:manage_users')->prefix('users')->group(function () {
        Route::get('/', function () { return view('users.index'); })->name('users.index');
        Route::get('/create', function () { return view('users.create'); })->name('users.create');
        Route::get('/{id}/edit', function () { return view('users.edit'); })->name('users.edit');
    });

    // Menu Management
    Route::middleware('permission:manage_menu')->prefix('menu')->group(function () {
        Route::get('/', function () { return view('menu.index'); })->name('menu.index');
        Route::get('/create', function () { return view('menu.create'); })->name('menu.create');
        Route::get('/{id}/edit', function () { return view('menu.edit'); })->name('menu.edit');
        // View menu for waiters/kitchen
        Route::get('/view', function () { return view('menu.view'); })->name('menu.view');
    });

    // Product Management
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::get('/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::get('/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::patch('/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::patch('/{product}/toggle-availability', [ProductController::class, 'toggleAvailability'])->name('products.toggle-availability');
        Route::get('/analytics/menu-engineering', [ProductController::class, 'menuEngineering'])->name('products.menu-engineering');
        Route::get('/recipes/bom', [ProductController::class, 'bomView'])->name('products.bom');
        Route::post('/update-cost-prices', [ProductController::class, 'updateAllCostPrices'])->name('products.update-cost-prices');
    });

    // Other Costs Management (Biaya Lain)
    Route::prefix('other-costs')->group(function () {
        Route::get('/', [OtherCostController::class, 'index'])->name('other-costs.index');
        Route::get('/create', [OtherCostController::class, 'create'])->name('other-costs.create');
        Route::post('/', [OtherCostController::class, 'store'])->name('other-costs.store');
        Route::get('/{otherCost}/edit', [OtherCostController::class, 'edit'])->name('other-costs.edit');
        Route::put('/{otherCost}', [OtherCostController::class, 'update'])->name('other-costs.update');
        Route::delete('/{otherCost}', [OtherCostController::class, 'destroy'])->name('other-costs.destroy');
    });

    // Category Management
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::patch('/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    });

    // Ingredient Management
    Route::prefix('ingredients')->group(function () {
        Route::get('/', [IngredientController::class, 'index'])->name('ingredients.index');
        Route::get('/create', [IngredientController::class, 'create'])->name('ingredients.create');
        Route::post('/', [IngredientController::class, 'store'])->name('ingredients.store');
        Route::get('/{ingredient}', [IngredientController::class, 'show'])->name('ingredients.show');
        Route::get('/{ingredient}/edit', [IngredientController::class, 'edit'])->name('ingredients.edit');
        Route::put('/{ingredient}', [IngredientController::class, 'update'])->name('ingredients.update');
        Route::delete('/{ingredient}', [IngredientController::class, 'destroy'])->name('ingredients.destroy');
        Route::get('/analytics/low-stock', [IngredientController::class, 'lowStock'])->name('ingredients.low-stock');
        Route::get('/analytics/near-expiry', [IngredientController::class, 'nearExpiry'])->name('ingredients.near-expiry');
    });

    // Promotions Management
    Route::middleware('permission:manage_promotions')->prefix('promotions')->group(function () {
        Route::get('/', function () { return view('promotions.index'); })->name('promotions.index');
        Route::get('/create', function () { return view('promotions.create'); })->name('promotions.create');
        Route::get('/{id}/edit', function () { return view('promotions.edit'); })->name('promotions.edit');
    });

    // Inventory Management
    Route::middleware('permission:manage_inventory')->prefix('inventory')->group(function () {
        Route::get('/', function () { return view('inventory.index'); })->name('inventory.index');
        Route::get('/dashboard', function () { return view('inventory.dashboard'); })->name('inventory.dashboard');
        Route::get('/suppliers', function () { return view('inventory.suppliers'); })->name('inventory.suppliers');
        Route::get('/update', function () { return view('inventory.update'); })->name('inventory.update');
        Route::get('/opname', function () { return view('inventory.opname'); })->name('inventory.opname');
    });

    // Stock Reports
    Route::middleware('permission:view_stock_reports')->group(function () {
        Route::get('/inventory/reports', function () { return view('inventory.reports'); })->name('inventory.reports');
    });

    // Monitoring
    Route::middleware('permission:view_dashboard')->group(function () {
        Route::get('/monitoring/live', function () { return view('monitoring.live'); })->name('monitoring.live');
    });

    // Settings
    Route::middleware('permission:manage_settings')->prefix('settings')->group(function () {
        Route::get('/system', function () { return view('settings.system'); })->name('settings.system');
        Route::get('/backup', function () { return view('settings.backup'); })->name('settings.backup');
    });

    // Role Management
    Route::middleware('permission:manage_roles')->prefix('settings')->group(function () {
        Route::get('/roles', function () { return view('settings.roles'); })->name('settings.roles');
        Route::get('/roles/create', function () { return view('settings.roles.create'); })->name('settings.roles.create');
        Route::get('/roles/{id}/edit', function () { return view('settings.roles.edit'); })->name('settings.roles.edit');
    });

    // Shift Management
    Route::middleware('permission:manage_shifts')->prefix('shifts')->group(function () {
        Route::get('/', function () { return view('shifts.index'); })->name('shifts.index');
        Route::get('/create', function () { return view('shifts.create'); })->name('shifts.create');
        Route::get('/{id}/edit', function () { return view('shifts.edit'); })->name('shifts.edit');
    });

    // POS Routes
    Route::middleware('permission:access_pos')->prefix('pos')->group(function () {
        Route::get('/', function () { return view('pos.index'); })->name('pos.index');
        Route::get('/transaction', function () { return view('pos.transaction'); })->name('pos.transaction');
    });

    // Transaction Routes for Kasir
    Route::middleware('permission:view_daily_transactions')->prefix('transactions')->group(function () {
        Route::get('/today', function () { return view('transactions.today'); })->name('transactions.today');
        Route::get('/history', function () { return view('transactions.history'); })->name('transactions.history');
    });

    // Payment Routes
    Route::middleware('permission:handle_payments')->prefix('payments')->group(function () {
        Route::get('/process', function () { return view('payments.process'); })->name('payments.process');
        Route::get('/history', function () { return view('payments.history'); })->name('payments.history');
    });

    // Table Management for Waiters
    Route::middleware('permission:manage_tables')->prefix('tables')->group(function () {
        Route::get('/', function () { return view('tables.index'); })->name('tables.index');
        Route::get('/{id}', function () { return view('tables.show'); })->name('tables.show');
    });

    // Order Management
    Route::middleware('permission:create_orders')->prefix('orders')->group(function () {
        Route::get('/create', function () { return view('orders.create'); })->name('orders.create');
    });

    Route::middleware('permission:update_order_status')->prefix('orders')->group(function () {
        Route::get('/active', function () { return view('orders.active'); })->name('orders.active');
        Route::get('/{id}/update-status', function () { return view('orders.update-status'); })->name('orders.update-status');
    });

    // Kitchen Routes
    Route::middleware('permission:view_kitchen_display')->prefix('kitchen')->group(function () {
        Route::get('/display', function () { return view('kitchen.display'); })->name('kitchen.display');
        Route::get('/orders', function () { return view('kitchen.orders'); })->name('kitchen.orders');
    });

    Route::middleware('permission:view_menu_items')->prefix('kitchen')->group(function () {
        Route::get('/menu', function () { return view('kitchen.menu'); })->name('kitchen.menu');
    });

    // Stock Level Updates
    Route::middleware('permission:update_stock_levels')->prefix('inventory')->group(function () {
        Route::post('/update-stock', function () { return back(); })->name('inventory.update-stock');
    });

    // Supervisor Routes
    Route::middleware('permission:view_shift_reports')->prefix('supervisor')->group(function () {
        Route::get('/dashboard', function () { return view('supervisor.dashboard'); })->name('supervisor.dashboard');
        Route::get('/shifts', function () { return view('supervisor.shifts'); })->name('supervisor.shifts');
        Route::get('/daily-summary', function () { return view('supervisor.daily-summary'); })->name('supervisor.daily-summary');
    });

    // Approval Routes
    Route::middleware('permission:approve_voids')->prefix('supervisor')->group(function () {
        Route::get('/voids', function () { return view('supervisor.voids'); })->name('supervisor.voids');
        Route::post('/voids/{id}/approve', function () { return back(); })->name('supervisor.voids.approve');
    });

    Route::middleware('permission:approve_refunds')->prefix('supervisor')->group(function () {
        Route::get('/refunds', function () { return view('supervisor.refunds'); })->name('supervisor.refunds');
        Route::post('/refunds/{id}/approve', function () { return back(); })->name('supervisor.refunds.approve');
    });
});
