<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'name',
        'description',
        'weight',
        'calories',
        'caloriesPercentage',
        'proteins',
        'proteinsPercentage',
        'carbohydrates',
        'carbohydratesPercentage',
        'lipids',
        'lipidsPercentage',
        'sodium',
        'sodiumPercentage',
        'image',
        'country',
        'hideExtraInfo',
        'urlPdf',
        'active',
        'fiber',
        'fiberPercentage',
        'saturatedFats',
        'saturatedFatsPercentage',
        'transFats',
        'transFatsPercentage',
        'allergens',
        'sugarTotals'
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'calories' => 'integer',
        'caloriesPercentage' => 'integer',
        'proteins' => 'decimal:2',
        'proteinsPercentage' => 'integer',
        'carbohydrates' => 'decimal:2',
        'carbohydratesPercentage' => 'integer',
        'lipids' => 'decimal:2',
        'lipidsPercentage' => 'integer',
        'sodium' => 'decimal:2',
        'sodiumPercentage' => 'integer',
        'hideExtraInfo' => 'boolean',
        'active' => 'boolean',
        'fiber' => 'decimal:2',
        'fiberPercentage' => 'integer',
        'saturatedFats' => 'decimal:2',
        'saturatedFatsPercentage' => 'integer',
        'transFats' => 'decimal:2',
        'transFatsPercentage' => 'integer',
        'allergens' => 'array',
        'sugarTotals' => 'decimal:2',
        'price' => 'string',
    ];
}
