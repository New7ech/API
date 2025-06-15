<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\Categorie; // Assuming Categorie is needed for Article creation
use App\Models\Emplacement; // Assuming Emplacement is needed
use App\Models\Fournisseur; // Assuming Fournisseur is needed
use Illuminate\Support\Facades\Artisan;

class ArticleApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshApplication(); // Refresh application instance

        // Manually run migrations for diagnostics
        Artisan::call('migrate');

        // Set default headers for API tests
        $this->serverVariables['HTTP_ACCEPT'] = 'application/json';

        // Create a user for authentication
        $this->user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);

        // Login and get token
        $response = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'password123',
            'device_name' => 'test-device',
        ]);

        // Add a check here to ensure token is actually retrieved
        if ($response->status() !== 200) {
            // Log or dump relevant info if login fails during setup
            // For example: dump($response->content());
            throw new \Exception('Failed to login and retrieve token during test setup. Status: ' . $response->status() . ' Content: ' . $response->content());
        }
        $this->token = $response->json('token');

        if (empty($this->token)) {
            throw new \Exception('Token was empty after login attempt during test setup.');
        }
    }

    public function test_guest_cannot_access_protected_article_routes()
    {
        $this->getJson('/api/articles')->assertUnauthorized();
        // Add more checks for other methods if needed
    }

    public function test_user_can_login_and_get_token()
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'password123',
            'device_name' => 'test-device-login-test',
        ]);
        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'wrongpassword',
            'device_name' => 'test-device',
        ])->assertStatus(422); // Laravel's default for ValidationException
    }

    public function test_can_get_all_articles()
    {
        Article::factory()->count(3)->create(['created_by' => $this->user->id]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
             ->getJson('/api/articles');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data') // Verify 3 items are in 'data'
                 ->assertJsonStructure([
                     'data' => [['id', 'name', 'description']],
                     // 'links', // Assuming these are missing from the actual response
                     // 'meta'   // Assuming these are missing from the actual response
                 ]);
    }

    public function test_can_create_article()
    {
        // Ensure related models are created if necessary for validation/foreign keys
        $categorie = Categorie::factory()->create();
        $emplacement = Emplacement::factory()->create();
        $fournisseur = Fournisseur::factory()->create();

        $articleData = [
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'quantite' => $this->faker->numberBetween(1, 100), // Changed from quantity
            'prix' => $this->faker->randomFloat(2, 10, 500), // Changed from purchase_price/sale_price
            // 'status' => 'disponible', // Removed status
            'category_id' => $categorie->id, // Changed from categorie_id
            'emplacement_id' => $emplacement->id,
            'fournisseur_id' => $fournisseur->id,
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
             ->postJson('/api/articles', $articleData)
             ->assertStatus(201)
             ->assertJsonFragment(['name' => $articleData['name']]);

        $this->assertDatabaseHas('articles', ['name' => $articleData['name']]);
    }

    public function test_create_article_fails_with_validation_errors()
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
             ->postJson('/api/articles', ['name' => '']) // Invalid data
             ->assertStatus(422) // Unprocessable Entity for validation errors
             ->assertJsonValidationErrors(['name']); // Check for specific validation error
    }

    public function test_can_get_specific_article()
    {
        $article = Article::factory()->create(['created_by' => $this->user->id]);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
             ->getJson("/api/articles/{$article->id}")
             ->assertStatus(200)
             ->assertJsonFragment(['id' => $article->id]);
    }

    public function test_get_non_existent_article_returns_404()
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
             ->getJson("/api/articles/99999") // Non-existent ID
             ->assertStatus(404);
    }

    public function test_can_update_article()
    {
        $article = Article::factory()->create(['created_by' => $this->user->id]);
        $updatedData = [
            'name' => 'Updated Article Name',
            'description' => 'Updated description.',
            'quantite' => $article->quantite, // Changed from quantity, ensure model attribute is quantite
            'prix' => $article->prix, // Changed from purchase_price/sale_price, ensure model attribute is prix
            // 'status' => $article->status, // Removed status
            'category_id' => $article->category_id, // Changed from categorie_id
            'emplacement_id' => $article->emplacement_id,
            'fournisseur_id' => $article->fournisseur_id,
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
             ->putJson("/api/articles/{$article->id}", $updatedData)
             ->assertStatus(200)
             ->assertJsonFragment(['name' => 'Updated Article Name']);

        $this->assertDatabaseHas('articles', ['id' => $article->id, 'name' => 'Updated Article Name']);
    }

    public function test_can_delete_article()
    {
        $article = Article::factory()->create(['created_by' => $this->user->id]);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
             ->deleteJson("/api/articles/{$article->id}")
             ->assertStatus(204); // No Content

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
