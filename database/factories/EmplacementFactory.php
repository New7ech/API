<?php

namespace Database\Factories;

use App\Models\Emplacement;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmplacementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Emplacement::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
