<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'page',
        'userinfo'
    ];

    protected $hidden = [
        'userinfo',
        'updated_at',
    ];

    protected $casts = [
        // 'phone' => 'array',
        // 'page' => 'array',
        // 'userinfo' => 'array'
    ];

    
}
