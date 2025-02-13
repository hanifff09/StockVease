<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create([
            'nama' => 'Elektronik',
            'image' => 'Category/electronik.jpg',
            'deskripsi' => 'Barang-barang elektronik kantor',
        ]);
        
        Category::create([
            'nama' => 'Furnitur',
            'image' => 'Category/furniture.jpg',
            'deskripsi' => 'Peralatan furnitur kantor',
        ]);

        Category::create([
            'nama' => 'Peralatan Kantor',
            'image' => 'Category/tool.jpg',
            'deskripsi' => 'Printer, scanner, dan lainnya',
        ]);
    }
}

