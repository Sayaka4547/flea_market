<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'user_id'     => \App\Models\User::factory(),
        'postal_code' => '000-0000',
        'address'     => 'テスト住所',
        'building'    => '',
        ];
    }
}
