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
            'kondisi' => '100%',
        ]);
        Kondisi::create([
            'kondisi' => '90%',
        ]);
        Kondisi::create([
            'kondisi' => '80%',
        ]);
    }
}

