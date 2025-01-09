<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        // DB::table('items')->insert([
        //     ['name' => 'Laptop Dell XPS', 'category_id' => 1, 'stock' => 5, 'condition' => 'good', 'description' => 'Laptop untuk keperluan meeting'],
        //     ['name' => 'Meja Kantor', 'category_id' => 2, 'stock' => 10, 'condition' => 'good', 'description' => 'Meja kerja untuk staf'],
        //     ['name' => 'Printer Canon', 'category_id' => 3, 'stock' => 3, 'condition' => 'good', 'description' => 'Printer kantor untuk mencetak dokumen'],
        // ]);
    }
}
