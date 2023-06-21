<?php

namespace Dwikipeddos\PeddosLaravelTools\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OtpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'code' => rand(1000, 9999)
        ];
    }
}
