<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CompanyFactory extends Factory
{

    public function definition()
    {

        return [
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
