<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishRating extends Model
{
    use HasFactory;

    public $fillable = [
        'rating',
        'user_id',
    ];
}
