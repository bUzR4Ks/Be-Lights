<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminOrderController;

/*
|--------------------------------------------------------------------------
| Public Routes (User - Tanpa Login)
|--------------------------------------------------------------------------
*/

// Beranda: tampilkan dua section (Nasi Uduk & Aneka Semur)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Detail satu section: lihat daftar produk sesuai section (nasi_uduk / aneka_semur)
Route::get('/section/{section}', [HomeController::class, 'section'])->name('home.section');

// Keranjang
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{section}/{productId}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{section}/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');


// Checkout & Konfirmasi Pesanan
Route::get('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('order.place');

// Pesanan Saya (status pesanan & konfirmasi)
Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('order.my_orders');


/*
|--------------------------------------------------------------------------
| Redirect Dashboard (Optional untuk fix error "Route [dashboard] not defined")
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->name('dashboard');


/*
|--------------------------------------------------------------------------
| Admin Routes (Harus Login & Role Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {

    // Halaman dashboard admin
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

    //Halaman Laporan penjualan
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');

     
    // Validasi pembayaran (accept / deny)
    Route::post('/validate-payment/{order}', [AdminController::class, 'validatePayment'])->name('admin.validate_payment');

    // Update status pesanan (in_progress / completed)
    Route::post('/update-order-status/{order}', [AdminController::class, 'updateOrderStatus'])->name('admin.update_order_status');
});

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    // 💡 Tambahan route khusus untuk form dan store offline orders (DULUAN)
    Route::get('orders/create-offline', [AdminOrderController::class, 'createOffline'])->name('orders.create_offline');
    Route::post('orders/store-offline', [AdminOrderController::class, 'storeOffline'])->name('orders.store_offline');

    // Resource route untuk CRUD orders (index, create, store, edit, dll)
    Route::resource('orders', AdminOrderController::class)->except(['show']);
});

Route::post('/cart/bundling', [CartController::class, 'addBundling'])->name('cart.add.bundling');

require __DIR__.'/auth.php';
