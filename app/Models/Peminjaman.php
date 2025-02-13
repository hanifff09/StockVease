<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory, Uuid;

    protected $table  = 'peminjaman';

    protected $fillable = [
        'nama',
        'email',
        'nip',
        'alasan_pinjam',
        'item',
        'status',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
    ];

    public static function findByUuid($uuid) 
    {
        return self::where('uuid', $uuid)->firstOrFail();
    }
}
