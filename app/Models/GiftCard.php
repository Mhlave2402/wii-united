<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'value', 'status', 'expiry_date'];

    protected $casts = [
        'expiry_date' => 'datetime',
    ];
}
