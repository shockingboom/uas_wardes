<?php

use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\OrderController;

// Login Admin
Route::get('/admin/login', [AdminauthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::get('/', [OrderController::class, 'index'])->name('customer.menu');
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/history', [OrderController::class, 'history'])->name('order.history');

Route::prefix('/admin')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // CRUD Menu (Item)
    Route::resource('item', ItemController::class, ['as' => 'admin']);
    Route::resource('table', TableController::class, ['as' => 'admin']);
    Route::patch('/table/{table}/regenerate', [TableController::class, 'regenerateToken'])->name('admin.table.regenerate');
    Route::get('/table/{table}/qrcode', [TableController::class, 'showQrCode'])->name('admin.table.qrcode');
    Route::get('/table/{table}/qrcode/generate', [TableController::class, 'generateQrCode'])->name('admin.table.qrcode.generate');
    Route::get('/table/{table}/qrcode/download', [TableController::class, 'downloadQrCode'])->name('admin.table.qrcode.download');

    // Pesanan
    Route::get('/pesanan', [PesananController::class, 'index'])->name('admin.pesanan.index');
    Route::patch('/pesanan/{pesanan}/status', [PesananController::class, 'updateStatus'])->name('admin.pesanan.status');
});

// ============================
// 👤 Profile User
// ============================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';
