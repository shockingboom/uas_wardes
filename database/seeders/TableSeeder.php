<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = [
            '1',
            '2',
            '3',
            '4',
            '5',
            'VIP-01',
            'VIP-02',
            'Outdoor A',
            'Outdoor B',
        ];

        foreach ($tables as $nomorMeja) {
            Table::create([
                'nomer_meja' => $nomorMeja,
                'token' => Table::generateToken(),
            ]);
        }
    }
}
