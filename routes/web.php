<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\BudidayaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ─── PUBLIC ───────────────────────────────────────────────────────────────────

Route::get('/', function () {
    $katalog = \App\Models\KatalogIkan::with('jenisIkan')->where('tersedia', true)->take(8)->get();
    return view('welcome', compact('katalog'));
})->name('home');

// Katalog (public, tanpa login)
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
Route::get('/katalog/{katalog}', [KatalogController::class, 'show'])->name('katalog.show');

// ─── GUEST ONLY ───────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    // Google OAuth
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

    // Lupa Password (tanpa email — cek DB langsung)
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'checkEmail'])->name('password.email');
    Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

// ─── AUTH REQUIRED ────────────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {

    // Auth misc
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile/edit', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Dashboard utama — redirect sesuai role di DashboardController
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Keranjang & Pesanan (Customer) ────────────────────────────────────────
    Route::get('/cart', [PesananController::class, 'cart'])->name('cart');
    Route::post('/cart/add/{katalog}', [PesananController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/remove/{id}', [PesananController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/update/{id}', [PesananController::class, 'updateCart'])->name('cart.update');

    Route::post('/pesanan', [PesananController::class, 'store'])->name('pesanan.store');
    Route::get('/pesanan/{pesanan}', [PesananController::class, 'show'])->name('pesanan.show');
    Route::get('/pesanan-saya', [PesananController::class, 'list'])->name('pesanan.list');

    // ── Admin & Pemilik: kelola pesanan ──────────────────────────────────────
    Route::get('/pesanan-semua', [PesananController::class, 'allOrders'])->name('pesanan.all-orders');
    Route::post('/pesanan/{pesanan}/konfirmasi', [PesananController::class, 'konfirmasi'])->name('pesanan.konfirmasi');

    // ── Budidaya (Customer) ───────────────────────────────────────────────────
    Route::get('/budidaya', [BudidayaController::class, 'create'])->name('budidaya.create');
    Route::post('/budidaya', [BudidayaController::class, 'store'])->name('budidaya.store');
    Route::get('/budidaya/riwayat', [BudidayaController::class, 'index'])->name('budidaya.index');

    // ── Midtrans AJAX ─────────────────────────────────────────────────────────
    Route::post('/pembayaran/{pesanan}/snap-token', [PembayaranController::class, 'getSnapToken'])->name('pembayaran.snap-token');
    Route::post('/pembayaran/{pesanan}/success', [PembayaranController::class, 'handleSuccess'])->name('pembayaran.handle-success');
});

// ─── ADMIN ROUTES ─────────────────────────────────────────────────────────────

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Katalog / Menu Management
    Route::get('/menu', [AdminController::class, 'indexMenu'])->name('menu.index');
    Route::get('/menu/create', [AdminController::class, 'createMenu'])->name('menu.create');
    Route::post('/menu', [AdminController::class, 'storeMenu'])->name('menu.store');
    Route::post('/menu/bulk-destroy', [AdminController::class, 'destroyBulkMenu'])->name('menu.bulk-destroy');
    Route::delete('/menu/all', [AdminController::class, 'destroyAllMenu'])->name('menu.destroy-all');
    Route::get('/menu/{menu}/edit', [AdminController::class, 'editMenu'])->name('menu.edit');
    Route::put('/menu/{menu}', [AdminController::class, 'updateMenu'])->name('menu.update');
    Route::delete('/menu/{menu}', [AdminController::class, 'destroyMenu'])->name('menu.destroy');

    // Order Management
    Route::get('/order', [AdminController::class, 'indexOrder'])->name('order.index');
    Route::get('/order/{order}', [AdminController::class, 'showOrder'])->name('order.show');
    Route::patch('/order/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('order.status');
    Route::delete('/order/{order}', [AdminController::class, 'destroyOrder'])->name('order.destroy');

    // Pre-Order Management
    Route::get('/pre-order', [AdminController::class, 'indexPreOrder'])->name('pre-order.index');
    Route::get('/pre-order/{preOrder}', [AdminController::class, 'showPreOrder'])->name('pre-order.show');
    Route::patch('/pre-order/{preOrder}/status', [AdminController::class, 'updatePreOrderStatus'])->name('pre-order.status');

    // User Management
    Route::get('/user', [AdminController::class, 'indexUser'])->name('user.index');
    Route::get('/user/create', [AdminController::class, 'createUser'])->name('user.create');
    Route::post('/user', [AdminController::class, 'storeUser'])->name('user.store');
    Route::get('/user/{user}', [AdminController::class, 'showUser'])->name('user.show');
    Route::get('/user/{user}/edit', [AdminController::class, 'editUser'])->name('user.edit');
    Route::put('/user/{user}', [AdminController::class, 'updateUser'])->name('user.update');
    Route::delete('/user/{user}', [AdminController::class, 'destroyUser'])->name('user.destroy');

    // Budidaya Management
    Route::get('/budidaya', [AdminController::class, 'indexBudidaya'])->name('budidaya.index');
    Route::patch('/budidaya/{penawaranBudidaya}/status', [AdminController::class, 'updateStatusBudidaya'])->name('budidaya.status');

    // Reports — hanya pemilik yang bisa akses (dicek di controller)
    Route::get('/reports/sales', [AdminController::class, 'salesReport'])->name('report.sales');
    Route::get('/reports/sales/print', [AdminController::class, 'printSalesReport'])->name('report.sales.print');
    Route::get('/reports/financial', [AdminController::class, 'financialReport'])->name('report.financial');
});

// ─── PEMILIK ROUTES ───────────────────────────────────────────────────────────

Route::middleware('auth')->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/', [PemilikController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [PemilikController::class, 'dashboard'])->name('dashboard.view');
    Route::get('/sales-report', [PemilikController::class, 'salesReport'])->name('sales-report');
    Route::get('/sales-report/print', [PemilikController::class, 'printSalesReport'])->name('sales-report.print');
});



// ─── MIDTRANS WEBHOOK (exclude dari CSRF di VerifyCsrfToken) ──────────────────

Route::post('/midtrans/callback', [PembayaranController::class, 'callback'])->name('midtrans.callback');
