<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category',
        'price',
        'calories',
        'fat',
        'carbs',
        'protein',
        'label',
        'restaurantId',
        'image',
        'favorate',
        'rate'
    ];
}
