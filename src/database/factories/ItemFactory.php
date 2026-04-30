<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ItemFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id'     => User::factory(),
            'name'        => $this->faker->word(),
            'bland'       => $this->faker->word(),
            'price'       => $this->faker->numberBetween(100, 100000),
            'condition'   => '良好',
            'description' => $this->faker->sentence(),
            'image'       => 'items/img01.jpg',
            'status'      => 'on_sale',
        ];
    }
}