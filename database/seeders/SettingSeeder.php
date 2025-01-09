<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->truncate();

        Setting::create([
            'app' => 'StockVease',
            'description' =>  'Aplikasi StockVease',
            'logo' =>  '/media/misc/logotp.png',
            'bg_auth' =>  '/media/misc/bgstock.jpg',
            'banner' =>  '/media/misc/bannerstock.jpg',
            'pemerintah' =>  'Pemerintah Provinsi Jawa Timur',
            'dinas' =>  'Dinas Lingkungan Hidup',
            'alamat' =>  '',
            'telepon' =>  '',
            'email' =>  '',
        ]);
    }
}
