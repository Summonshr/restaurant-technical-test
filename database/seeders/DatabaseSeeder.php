<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\CustomUser;
use App\Models\Dish;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        CustomUser::create([
            'name' => 'Suman Shrestha',
            'email' => 'summonshr@gmail.com',
            'password' => 'password',
            'nickname' => 'summonshr',
        ]);
        CustomUser::create([
            'name' => 'Smeagol Shrestha',
            'email' => '_Sméagol_@gmail.com',
            'password' => 'password',
            'nickname' => '_Sméagol_',
        ]);
        Dish::create([
            'name' => 'Dish 1',
            'description' => 'Description Dish 1',
            'price' => 10,
        ]);

        Dish::create([
            'name' => 'Dish 2',
            'description' => 'Description Dish 2',
            'price' => 20,
        ]);

    }
}
