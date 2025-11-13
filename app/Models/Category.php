<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', // atau 'name' tergantung nama kolom di database
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}