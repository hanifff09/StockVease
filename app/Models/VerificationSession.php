<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationSession extends Model
{
    use HasFactory, Uuid;

    protected $fillable = [
        'name',
        'email',
        'verification_code',
        'session_token',
        'expires_at',
        'is_verified',
        'item_uuid'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_verified' => 'boolean'
    ];
    
}
