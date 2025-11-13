<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model CartItem (Item dalam Keranjang)
 * Model ini merepresentasikan item/produk yang ada di dalam keranjang belanja
 * Merupakan penghubung antara Cart dan Item (produk)
 */
class CartItem extends Model
{
    use HasFactory;

    /**
     * Kolom-kolom yang boleh diisi secara mass assignment
     * @var array
     */
    protected $fillable = [
        'cart_id',    // ID keranjang
        'item_id',    // ID produk
        'quantity',   // Jumlah produk
        'price',      // Harga satuan produk saat ditambahkan
    ];

    /**
     * Casting tipe data kolom
     * Memastikan quantity adalah integer dan price adalah float
     * @var array
     */
    protected $casts = [
        'quantity' => 'integer',
        'price' => 'float',
    ];

    /**
     * Relasi ke model Cart (Many to One / Belongs To)
     * Setiap cart item dimiliki oleh satu keranjang
     * 
     * @return BelongsTo
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Relasi ke model Item (Many to One / Belongs To)
     * Setiap cart item mereferensikan ke satu produk
     * 
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Method untuk mendapatkan subtotal (harga * quantity)
     * Menghitung total harga untuk item ini
     * 
     * @return float - Subtotal harga
     */
    public function getSubtotal(): float
    {
        return $this->price * $this->quantity;
    }

    /**
     * Method untuk menambah quantity
     * Menambahkan jumlah produk yang dipesan
     * 
     * @param int $amount - Jumlah yang akan ditambahkan (default 1)
     * @return bool - True jika berhasil update
     */
    public function incrementQuantity(int $amount = 1): bool
    {
        // Increment akan menambahkan nilai quantity dengan amount yang diberikan
        return $this->increment('quantity', $amount);
    }

    /**
     * Method untuk mengurangi quantity
     * Mengurangi jumlah produk yang dipesan
     * Jika quantity menjadi 0 atau kurang, item akan dihapus dari keranjang
     * 
     * @param int $amount - Jumlah yang akan dikurangi (default 1)
     * @return bool - True jika berhasil update atau delete
     */
    public function decrementQuantity(int $amount = 1): bool
    {
        // Kurangi quantity
        $this->decrement('quantity', $amount);
        
        // Refresh data dari database untuk mendapatkan nilai terbaru
        $this->refresh();
        
        // Jika quantity menjadi 0 atau kurang, hapus item dari keranjang
        if ($this->quantity <= 0) {
            return $this->delete();
        }
        
        return true;
    }

    /**
     * Method untuk update quantity ke nilai tertentu
     * Mengubah quantity menjadi nilai yang ditentukan
     * 
     * @param int $quantity - Nilai quantity baru
     * @return bool - True jika berhasil
     */
    public function updateQuantity(int $quantity): bool
    {
        // Jika quantity 0 atau kurang, hapus item
        if ($quantity <= 0) {
            return $this->delete();
        }
        
        // Update quantity ke nilai baru
        return $this->update(['quantity' => $quantity]);
    }
}