<?php

namespace App\Http\Controllers;

use App\Http\Requests\RateDishRequest;
use App\Models\Dish;

class DishRatingController extends Controller
{
    public function rateDish(RateDishRequest $request, Dish $dish)
    {
        $user = $request->user();

        if ($user->nickname === '_Sméagol_') {
            return response()->json(['message' => 'Sméagol cannot rate dishes at The Dancing Pony.'], 403);
        }

        if ($dish->ratings()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'You have already rated this dish.'], 400);
        }

        $rating = $request->input('rating');

        $dish->ratings()->create(['rating' => $rating, 'user_id' => $user->id]);

        return response()->json(['message' => 'Dish rated successfully']);
    }
}
