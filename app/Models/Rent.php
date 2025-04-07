<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'category_id',
        'title',
        'description',
        'price',
        'is_access',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(CategoryRent::class);
    }
}
