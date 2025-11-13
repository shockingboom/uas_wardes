<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Cart (Keranjang Belanja)
 * Model ini merepresentasikan keranjang belanja user
 * Setiap user bisa memiliki satu atau lebih keranjang (aktif dan yang sudah checkout)
 */
class Cart extends Model
{
    use HasFactory;

    /**
     * Kolom-kolom yang boleh diisi secara mass assignment
     * Mass assignment adalah pengisian data menggunakan create() atau update()
     * @var array
     */
    protected $fillable = [
        'user_id',    // ID user pemilik keranjang
        'status',     // Status keranjang (active/checked_out)
    ];

    /**
     * Relasi ke model User (Many to One / Belongs To)
     * Setiap keranjang dimiliki oleh satu user
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model CartItem (One to Many / Has Many)
     * Satu keranjang bisa memiliki banyak item
     * 
     * @return HasMany
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Method untuk mendapatkan keranjang aktif user
     * Jika tidak ada, otomatis membuat keranjang baru
     * 
     * @param int $userId - ID user yang keranjangnya akan diambil
     * @return Cart - Keranjang aktif user
     */
    public static function getActiveCart(int $userId): Cart
    {
        // Cari keranjang aktif user, jika tidak ada buat baru
        // firstOrCreate akan mencari data, jika tidak ada akan membuat baru
        return self::firstOrCreate(
            ['user_id' => $userId, 'status' => 'active'], // Kriteria pencarian
            ['user_id' => $userId, 'status' => 'active']  // Data yang akan dibuat jika tidak ditemukan
        );
    }

    /**
     * Method untuk menghitung total harga semua item di keranjang
     * 
     * @return float - Total harga
     */
    public function getTotalPrice(): float
    {
        // Sum() adalah method Laravel untuk menjumlahkan nilai kolom
        // Kita menjumlahkan subtotal dari setiap cart item
        return $this->cartItems->sum(function ($cartItem) {
            // Subtotal = harga * quantity
            return $cartItem->price * $cartItem->quantity;
        });
    }

    /**
     * Method untuk menghitung total jumlah item di keranjang
     * 
     * @return int - Total quantity
     */
    public function getTotalQuantity(): int
    {
        // Menjumlahkan quantity dari semua cart items
        return $this->cartItems->sum('quantity');
    }

    /**
     * Method untuk mengosongkan keranjang
     * Menghapus semua item di dalam keranjang
     * 
     * @return void
     */
    public function clearCart(): void
    {
        // Delete() akan menghapus semua cart items yang terkait
        $this->cartItems()->delete();
    }

    /**
     * Method untuk mengubah status keranjang menjadi checked_out
     * Dipanggil setelah user menyelesaikan pembelian
     * 
     * @return bool - True jika berhasil update
     */
    public function checkout(): bool
    {
        // Update status menjadi checked_out
        return $this->update(['status' => 'checked_out']);
    }
}