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
            'nama' => 'budi',
            'email'=> 'budi@gmail.com',
            'nip' => '08928378',
            'alasan_pinjam' => 'untuk presentasi di kementrian',
            'item' => 'HUAWEI MateBook D14 / Laptop Intel Core i5-1240P, 8GB RAM, 512GB SSD, Perak Mistik, Windows 11',
            'status' => '4',
            'tanggal_peminjaman' => '2025-01-01',
            'tanggal_pengembalian' => '2025-01-02',
        ]);
        Peminjaman::create([
            'nama' => 'joko',
            'email'=> 'joko@gmail.com',
            'nip' => '767848858',
            'alasan_pinjam' => 'untuk lembur mengerjakan revisi meeting kemaren',
            'item' => 'YODIF Lampu Meja Belajar LED Bracket Adjustable Warm White E27 - AT-1002 - Black',
            'status' => '4',
            'tanggal_peminjaman' => '2025-01-01',
            'tanggal_pengembalian' => '2025-01-02',
        ]);
        Peminjaman::create([
            'nama' => 'yono',
            'email'=> 'yono@gmail.com',
            'nip' => '3455326',
            'alasan_pinjam' => 'untuk menghitung pemasukan dan pengeluaran bulan ini',
            'item' => 'PIALONG Kalkulator Desktop Baterai Genggam',
            'status' => '4',
            'tanggal_peminjaman' => '2025-02-01',
            'tanggal_pengembalian' => '2025-02-02',
        ]);
    }
}
