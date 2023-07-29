<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image', 'price'];

    protected $hidden = ['ratings'];

    public function ratings()
    {
        return $this->hasMany(DishRating::class);
    }

    public function users()
    {
        return $this->belongsToMany(CustomUser::class, 'dish_ratings', 'dish_id', 'user_id')->withPivot('rating');
    }

    public function rating(): Attribute
    {
        return new Attribute(fn () => $this->ratings->avg('rating') ?? 0);
    }
}
