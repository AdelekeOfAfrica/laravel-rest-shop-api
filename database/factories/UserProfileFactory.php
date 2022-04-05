<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'firstname' => $this->faker->firstName(),
            'Lastname' => $this->faker->LastName(),
            'gender' =>$this->faker->randomElement(['male','female','trans person','others']),
            'active' => $this->faker->boolean(),
        ];
    }
}
