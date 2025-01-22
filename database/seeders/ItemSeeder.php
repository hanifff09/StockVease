<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        Item::create([
            'nama' => 'Laptop huawei d14',
            'category_id' => '1',
            'kondisi_id' => '2',
            'stok' => '8',
            'deskripsi' => 'Laptop huawei d14 dengan spesifikasi lengkap mantap',
            'image' => 'Item/bowl.png',
        ]);
        Item::create([
            'nama' => 'hp sumsang a55 milik seseorang',
            'category_id' => '2',
            'kondisi_id' => '1',
            'stok' => '6',
            'deskripsi' => 'hape enak dengan spesifikasi lengkap mantap',
            'image' => 'Item/sumsang.jpg',
        ]);
    }
}
