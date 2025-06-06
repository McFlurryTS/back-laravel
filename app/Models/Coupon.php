<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'additional_info',
        'icon',
        'valid_until',
        'active',
        'nivel',
    ];
}