<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            // Makanan
            [
                'nama' => 'Nasi Goreng Spesial',
                'harga' => 25000,
                'kategori' => 'Makanan',
                'gambar' => null,
            ],
            [
                'nama' => 'Mie Goreng',
                'harga' => 20000,
                'kategori' => 'Makanan',
                'gambar' => null,
            ],
            [
                'nama' => 'Ayam Geprek',
                'harga' => 18000,
                'kategori' => 'Makanan',
                'gambar' => null,
            ],
            [
                'nama' => 'Sate Ayam',
                'harga' => 30000,
                'kategori' => 'Makanan',
                'gambar' => null,
            ],
            [
                'nama' => 'Gado-Gado',
                'harga' => 15000,
                'kategori' => 'Makanan',
                'gambar' => null,
            ],
            
            // Minuman
            [
                'nama' => 'Es Teh Manis',
                'harga' => 5000,
                'kategori' => 'Minuman',
                'gambar' => null,
            ],
            [
                'nama' => 'Es Jeruk',
                'harga' => 8000,
                'kategori' => 'Minuman',
                'gambar' => null,
            ],
            [
                'nama' => 'Kopi Susu',
                'harga' => 12000,
                'kategori' => 'Minuman',
                'gambar' => null,
            ],
            [
                'nama' => 'Jus Alpukat',
                'harga' => 15000,
                'kategori' => 'Minuman',
                'gambar' => null,
            ],
            [
                'nama' => 'Es Campur',
                'harga' => 18000,
                'kategori' => 'Minuman',
                'gambar' => null,
            ],
            
            // Dessert
            [
                'nama' => 'Pisang Goreng',
                'harga' => 10000,
                'kategori' => 'Dessert',
                'gambar' => null,
            ],
            [
                'nama' => 'Martabak Manis',
                'harga' => 35000,
                'kategori' => 'Dessert',
                'gambar' => null,
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
