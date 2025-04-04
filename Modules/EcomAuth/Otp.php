<?php

namespace Modules\EcomAuth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'phone_number',
        'otp_code',
        'reference_id',
        'expires_at',
        'verified',
        'verified_at',
        'attempts'
    ];
    
    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'verified' => 'boolean',
    ];
}