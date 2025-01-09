<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kondisi extends Model
{
    use HasFactory, Uuid;
    
    protected $fillable = [
        'kondisi',
    ];

    public function Item()
    {
        return $this->hasMany(Item::class);
    }
}
