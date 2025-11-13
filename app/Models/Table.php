<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomer_meja',
        'token',
    ];

    /**
     * Generate unique token for table
     */
    public static function generateToken()
    {
        do {
            $token = Str::random(8);
        } while (self::where('token', $token)->exists());

        return $token;
    }

    /**
     * Relasi ke model Pesanan (Orders)
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Pesanan::class);
    }
}
