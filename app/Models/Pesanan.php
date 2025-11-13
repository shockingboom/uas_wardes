<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'table_id',
        'token',
        'items',
        'total',
        'status',
        'payment_method',
        'customer_name',
        'customer_phone',
        'notes',
    ];

    protected $casts = [
        'items' => 'array',
        'total' => 'decimal:2',
    ];

    /**
     * Relasi ke model Table
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
}
