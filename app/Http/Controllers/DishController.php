<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyDishRequest;
use App\Http\Requests\StoreDishRequest;
use App\Http\Requests\UpdateDishRequest;
use App\Models\Dish;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function store(StoreDishRequest $request)
    {
        $dish = Dish::create($request->all());

        return response()->json(['message' => 'Dish created successfully', 'dish' => $dish], 201);
    }

    public function index(Request $request)
    {
        $query = $request->input('query');

        $dishes = Dish::with('ratings')->when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('name', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%');
        })->paginate($request->input('limit', 10));

        $dishes->setCollection(collect($dishes->items())->map->append('rating'));

        return response()->json($dishes);
    }

    public function show(Dish $dish)
    {
        $dish->load('ratings');

        $dish->append('rating');

        return response()->json(['data' => $dish]);
    }

    public function update(UpdateDishRequest $request, Dish $dish)
    {
        $dish->update($request->all());

        return response()->json(['message' => 'Dish updated successfully', 'dish' => $dish]);
    }

    public function destroy(DestroyDishRequest $request, Dish $dish)
    {

        $dish->delete();

        return response()->json(['message' => 'Dish deleted successfully']);
    }
}
