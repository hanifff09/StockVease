<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Peminjaman;

class PeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Peminjaman::create([
            'nama' => 'vyslen',
            'email'=> 'vys@gmail.com',
            'nip' => '12345678',
            'alasan_pinjam' => 'sakit hati dan menguatkan',
            'item' => 'Laptop huawei d14',
            'status' => '4',
            'tanggal_peminjaman' => '2022-01-01',
            'tanggal_pengembalian' => '2022-01-02',
        ]);
    }
}
