<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Models\Emplacement;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence,
            'prix' => $this->faker->randomFloat(2, 1, 1000),
            'quantite' => $this->faker->numberBetween(1, 100),
            'category_id' => Categorie::factory(),
            'fournisseur_id' => Fournisseur::factory(),
            'emplacement_id' => Emplacement::factory(),
            'created_by' => User::factory(),
        ];
    }
}
