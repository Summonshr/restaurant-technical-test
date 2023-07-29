<?php

namespace Tests\Feature;

use App\Models\CustomUser;
use App\Models\Dish;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DishApiTest extends TestCase
{
    use RefreshDatabase;

    public function testAuthenticatedUsersCanCreateDish()
    {
        $user = CustomUser::factory()->create();

        $this->actingAs($user);

        $data = [
            'name' => 'New Dish',
            'description' => 'This is a new dish',
            'image' => 'https://example.com/dish.jpg',
            'price' => 9.99,
        ];

        $response = $this->postJson('/api/dishes', $data);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Dish created successfully']);

        $this->assertDatabaseHas('dishes', $data);
    }

    public function testAuthenticatedUsersCanReadDishes()
    {
        Dish::factory()->count(5)->create();

        $user = CustomUser::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson('/api/dishes');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure(['data' => [['id', 'name', 'description', 'image', 'price', 'rating']]]);
    }

    public function testAuthenticatedUsersCanReadSingleDish()
    {
        $dish = Dish::factory()->create();

        $user = CustomUser::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson('/api/dishes/' . $dish->id);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['id', 'name', 'description', 'image', 'price', 'rating']]);
    }

    public function testAuthenticatedUsersCanUpdateDish()
    {
        $user = CustomUser::factory()->create();
        $this->actingAs($user);

        $dish = Dish::factory()->create();

        $data = [
            'name' => 'Updated Dish',
            'description' => 'This is an updated dish',
            'price' => 12.99,
        ];

        $response = $this->putJson('/api/dishes/' . $dish->id, $data);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Dish updated successfully']);

        $this->assertDatabaseHas('dishes', $data);
    }

    public function testAuthenticatedUsersCanDeleteDish()
    {
        $user = CustomUser::factory()->create();
        $this->actingAs($user);

        $dish = Dish::factory()->create();

        $response = $this->deleteJson('/api/dishes/' . $dish->id);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Dish deleted successfully']);

        $this->assertDatabaseMissing('dishes', $dish->toArray());
    }

    public function testAuthenticatedUsersCanRateDish()
    {
        $user = CustomUser::factory()->create();
        $this->actingAs($user);

        $dish = Dish::factory()->create();

        $data = [
            'rating' => 5,
        ];

        $response = $this->postJson('/api/dishes/' . $dish->id . '/rate', $data);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Dish rated successfully']);


        $response = $this->getJson('/api/dishes/' . $dish->id)->assertJsonStructure(['data' => [
            'id', 'name', 'description', 'image', 'price', 'rating'
        ]])->assertJson(['data' => ['rating' => 5]]);


        $this->assertDatabaseHas('dish_ratings', [
            'dish_id' => $dish->id,
            'user_id' => $user->id,
            'rating' => 5,
        ]);
    }

    public function testUserSmeagolCannotRateAnyDish()
    {
        $smeagol = CustomUser::factory()->create(['name' => 'Sméagol', 'nickname' => '_Sméagol_']);

        $dish = Dish::factory()->create();

        $this->actingAs($smeagol);

        $response = $this->postJson('/api/dishes/' . $dish->id . '/rate', ['rating' => 5]);

        $response->assertStatus(403)
            ->assertJson(['message' => 'Sméagol cannot rate dishes at The Dancing Pony.']);
    }

    public function testUnauthenticatedUsersCannotAccessProtectedEndpoints()
    {
        $dish = Dish::factory()->create();

        $response = $this->getJson('/api/dishes');

        $response->assertStatus(401);

        $response = $this->getJson("/api/dishes/{$dish->id}");

        $response->assertStatus(401);

        $response = $this->postJson("/api/dishes/{$dish->id}/rate", ['rating' => 3.5]);

        $response->assertStatus(401);

        $response = $this->putJson("/api/dishes/{$dish->id}", ['name' => 'Updaated Dish']);

        $response->assertStatus(401);

        $response = $this->deleteJson("/api/dishes/{$dish->id}");

        $response->assertStatus(401);
    }
}
