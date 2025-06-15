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
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'prix' => $this->faker->randomFloat(2, 10, 500), // Changed from purchase/sale_price
            'quantite' => $this->faker->numberBetween(1, 100), // Changed from quantity
            // 'image' => null, // Removed, not in migration
            // 'status' => $this->faker->randomElement(['disponible', 'en rupture', 'commande']), // Removed, not in migration
            'created_by' => User::factory(),
            'category_id' => Categorie::factory(), // Changed from categorie_id
            'emplacement_id' => Emplacement::factory(),
            'fournisseur_id' => Fournisseur::factory(),
        ];
    }
}
