<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kondisi;

class KondisiSeeder extends Seeder
{
    public function run()
    {
        Kondisi::create([
            'kondisi' => 'Sangat Baik',
        ]);
        Kondisi::create([
            'kondisi' => 'Cukup Baik',
        ]);
        Kondisi::create([
            'kondisi' => 'Baik',
        ]);
    }
}

