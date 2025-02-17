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
            'nama' => 'HUAWEI MateBook D14 / Laptop Intel Core i5-1240P, 8GB RAM, 512GB SSD, Perak Mistik, Windows 11',
            'category_id' => '1',
            'kondisi_id' => '2',
            'stok' => '4',
            'deskripsi' => 'HUAWEI MATEBOOK D14 hadir dengan performa unggul berkat Prosesor Intel Core i5-1240P dan Intel Iris X Graphics yang meningkatkan pengalaman visual Anda. Dilengkapi 8GB LPDDR4X dan 512GB NVMe PCIe SSD, Anda dapat menjalankan berbagai aplikasi dan menyimpan banyak data tanpa kendala. Layar 14 inci IPS dengan rasio layar-ke-tubuh 90% dan resolusi 1920x1200 memastikan visual yang jernih dan cerah, sementara desain bezel sempit memberi tampilan modern. Dengan berat hanya 1,39kg, laptop ini sangat portabel dan nyaman untuk dibawa. Nikmati konektivitas nirkabel terbaik dengan Wifi 6 dan berbagai port seperti USB-C untuk kemudahan penggunaan.',
            'image' => 'Item/laptop.jpg',
        ]);
        Item::create([
            'nama' => 'YODIF Lampu Meja Belajar LED Bracket Adjustable Warm White E27 - AT-1002 - Black',
            'category_id' => '2',
            'kondisi_id' => '1',
            'stok' => '6',
            'deskripsi' => 'Untuk membantu Anda agar semakin mudah saat membaca dan menulis ketika belajar, maka Anda harus memiliki lampu meja belajar dari YODIF. Lampu meja belajar dirancang untuk memberikan perlindungan mata yang sangat baik. Sudut kepala lampu bisa diatur dengan mudah begitu juga dengan bracket-nya. Menggunakan socket lampu E27 yang mudah ditemui. Dilengkapi juga dengan switch untuk memudahkan mematikan dan menyalakan lampu.',
            'image' => 'Item/lampu.jpg',
        ]);
        Item::create([
            'nama' => 'PIALONG Kalkulator Desktop Baterai Genggam',
            'category_id' => '3',
            'kondisi_id' => '2',
            'stok' => '6',
            'deskripsi' => 'Kalkulator Desktop Baterai Genggam Kalkulator Daya Ganda Tenaga Surya Layar LED Tampilan HD Digital 12-Bit Kalkulator Penghapus Perlindungan Mata Perhitungan Akurat',
            'image' => 'Item/kalku.jpg',
        ]);
    }
}
