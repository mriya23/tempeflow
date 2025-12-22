<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StorefrontController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'storefront.landing')->name('home');

Route::get('/produk', [StorefrontController::class, 'products'])->name('storefront.products');
Route::get('/lacak-pesanan', [StorefrontController::class, 'track'])->name('storefront.track');
Route::get('/pesanan-saya', [StorefrontController::class, 'myOrders'])->middleware(['auth'])->name('storefront.my-orders');
Route::post('/pesanan/batalkan/{order}', [StorefrontController::class, 'cancelOrder'])->middleware(['auth'])->name('storefront.order.cancel');

Route::post('/keranjang/tambah', [StorefrontController::class, 'cartAdd'])->name('storefront.cart.add');
Route::post('/keranjang/update', [StorefrontController::class, 'cartUpdate'])->name('storefront.cart.update');
Route::post('/keranjang/hapus', [StorefrontController::class, 'cartRemove'])->name('storefront.cart.remove');
Route::get('/keranjang', [StorefrontController::class, 'cart'])->name('storefront.cart');

Route::post('/checkout', [StorefrontController::class, 'checkout'])->name('storefront.checkout');
Route::get('/bayar/{code}', [StorefrontController::class, 'pay'])->middleware(['auth'])->name('storefront.pay');
Route::get('/checkout/sukses/{code}', [StorefrontController::class, 'checkoutSuccess'])->name('storefront.checkout.success');
Route::get('/checkout/status/{code}', [StorefrontController::class, 'checkoutStatus'])->middleware(['auth'])->name('storefront.checkout.status');
Route::post('/checkout/charge/{code}', [StorefrontController::class, 'chargePayment'])->middleware(['auth'])->name('storefront.checkout.charge');

Route::post('/midtrans/notification', [MidtransWebhookController::class, 'handle'])->name('midtrans.notification');

Route::get('/dashboard', function () {
    if (Auth::user() && (string) (Auth::user()->role ?? '') === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', EnsureAdmin::class])
    ->name('admin.dashboard');

Route::post('/admin/orders/{order}/status', [AdminDashboardController::class, 'updateStatus'])
    ->middleware(['auth', EnsureAdmin::class])
    ->name('admin.orders.updateStatus');

Route::get('/admin/orders/export', [AdminDashboardController::class, 'export'])
    ->middleware(['auth', EnsureAdmin::class])
    ->name('admin.orders.export');

Route::get('/admin/orders/{order}/invoice', [AdminDashboardController::class, 'invoice'])
    ->middleware(['auth', EnsureAdmin::class])
    ->name('admin.orders.invoice');

Route::get('/admin/reports/pdf', [AdminDashboardController::class, 'reportPdf'])
    ->middleware(['auth', EnsureAdmin::class])
    ->name('admin.reports.pdf');

Route::get('/admin/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])
    ->middleware(['auth', EnsureAdmin::class])
    ->name('admin.users.index');

Route::get('/admin/users/{user}', [\App\Http\Controllers\Admin\AdminUserController::class, 'show'])
    ->middleware(['auth', EnsureAdmin::class])
    ->name('admin.users.show');

Route::resource('admin/products', \App\Http\Controllers\Admin\AdminProductController::class)
    ->names('admin.products')
    ->middleware(['auth', EnsureAdmin::class]);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ============================================
// TEMPORARY ROUTES FOR DEPLOYMENT (DELETE AFTER USE!)
// ============================================

// Run migrations via web (visit: /deploy/migrate?key=your_secret_key)
Route::get('/deploy/migrate', function () {
    if (request('key') !== 'tempejaya2025secret') {
        abort(403, 'Unauthorized');
    }

    try {
        Artisan::call('migrate', ['--force' => true]);
        return '<pre>Migration completed successfully!<br><br>' . Artisan::output() . '</pre>';
    } catch (\Exception $e) {
        return '<pre>Migration failed: ' . $e->getMessage() . '</pre>';
    }
});

// Run database seeder (visit: /deploy/seed?key=your_secret_key)
Route::get('/deploy/seed', function () {
    if (request('key') !== 'tempejaya2025secret') {
        abort(403, 'Unauthorized');
    }

    try {
        Artisan::call('db:seed', ['--force' => true]);
        return '<pre>Seeding completed successfully!<br><br>' . Artisan::output() . '</pre>';
    } catch (\Exception $e) {
        return '<pre>Seeding failed: ' . $e->getMessage() . '</pre>';
    }
});

// Clear all cache (visit: /deploy/clear-cache?key=your_secret_key)
Route::get('/deploy/clear-cache', function () {
    if (request('key') !== 'tempejaya2025secret') {
        abort(403, 'Unauthorized');
    }

    try {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        return '<pre>All cache cleared successfully!</pre>';
    } catch (\Exception $e) {
        return '<pre>Clear cache failed: ' . $e->getMessage() . '</pre>';
    }
});

// Create storage link (visit: /deploy/storage-link?key=your_secret_key)
Route::get('/deploy/storage-link', function () {
    if (request('key') !== 'tempejaya2025secret') {
        abort(403, 'Unauthorized');
    }

    try {
        Artisan::call('storage:link');
        return '<pre>Storage link created successfully!</pre>';
    } catch (\Exception $e) {
        return '<pre>Storage link failed: ' . $e->getMessage() . '</pre>';
    }
});

// Generate APP_KEY (visit: /deploy/generate-key?key=your_secret_key)
Route::get('/deploy/generate-key', function () {
    if (request('key') !== 'tempejaya2025secret') {
        abort(403, 'Unauthorized');
    }

    try {
        Artisan::call('key:generate', ['--show' => true]);
        return '<pre>Generated APP_KEY (copy this to .env):<br><br>' . trim(Artisan::output()) . '</pre>';
    } catch (\Exception $e) {
        return '<pre>Key generation failed: ' . $e->getMessage() . '</pre>';
    }
});
