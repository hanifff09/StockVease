<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory, Uuid;

    protected $fillable = [
        'nama',
        'category_id',
        'kondisi_id',
        'stok',
        'deskripsi',
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }   

    public function kondisi()
    {
        return $this->belongsTo(Kondisi::class, 'kondisi_id');
    }
}
