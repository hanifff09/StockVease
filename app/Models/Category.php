<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, Uuid;

    protected $fillable = [
        'nama',
        'deskripsi',
        'image',
    ];

    public function Item()
    {
        return $this->hasMany(Item::class);
    }

}
