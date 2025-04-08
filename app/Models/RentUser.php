<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'rent_id',
        'message',
    ];

    public function rent()
    {
        return $this->belongsTo(Rent::class);
    }
}
