<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialNetworck extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_networks'
    ];

    protected $casts = [
        'social_networks' => 'array'
    ];
}
