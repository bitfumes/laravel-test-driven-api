<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebService extends Model
{
    use HasFactory;

    protected $casts = [
        'token' => 'json'
    ];

    protected $guarded = [];
}
