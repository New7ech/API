<?php

namespace Database\Factories;

use App\Models\Fournisseur;
use Illuminate\Database\Eloquent\Factories\Factory;

class FournisseurFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Fournisseur::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'nom_entreprise' => $this->faker->company,
            'telephone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'adresse' => $this->faker->address,
            'ville' => $this->faker->city,
            'pays' => $this->faker->country, // Added pays
            'description' => $this->faker->sentence,
        ];
    }
}
