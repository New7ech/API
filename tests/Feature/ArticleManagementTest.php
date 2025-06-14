<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Models\Emplacement;

class ArticleManagementTest extends TestCase
{
    use RefreshDatabase;

    // Méthode utilitaire pour créer et authentifier un utilisateur
    protected function authenticateUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    // Les méthodes de test seront ajoutées ici

    /** @test */
    public function test_peut_lister_articles()
    {
        $this->authenticateUser();
        Article::factory()->create(['name' => 'Article Alpha']);
        Article::factory()->create(['name' => 'Article Beta']);

        $response = $this->get(route('articles.index'));

        $response->assertStatus(200);
        $response->assertSee('Article Alpha');
        $response->assertSee('Article Beta');
    }

    /** @test */
    public function test_utilisateur_authentifie_peut_creer_article()
    {
        $user = $this->authenticateUser();

        $category = Categorie::factory()->create();
        $fournisseur = Fournisseur::factory()->create();
        $emplacement = Emplacement::factory()->create();

        $articleData = [
            'name' => 'New Awesome Article',
            'description' => 'This is a test description.',
            'prix' => 199.99,
            'quantite' => 25,
            'category_id' => $category->id,
            'fournisseur_id' => $fournisseur->id,
            'emplacement_id' => $emplacement->id,
        ];

        $response = $this->post(route('articles.store'), $articleData);

        $response->assertRedirect(route('articles.index'));
        $response->assertSessionHas('success', 'Article créé avec succès.');

        $this->assertDatabaseHas('articles', [
            'name' => 'New Awesome Article',
            'description' => 'This is a test description.',
            'prix' => 199.99,
            'quantite' => 25,
            'category_id' => $category->id,
            'fournisseur_id' => $fournisseur->id,
            'emplacement_id' => $emplacement->id,
            'created_by' => $user->id,
        ]);
    }

    /**
     * Vérifie que la création d'un article échoue si le nom n'est pas fourni.
     * @test
     */
    public function test_article_creation_requires_name()
    {
        $this->authenticateUser();

        $articleData = [
            'description' => 'Test desc without name',
            'prix' => 100,
            'quantite' => 10,
        ];

        $response = $this->post(route('articles.store'), $articleData);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('articles', ['description' => 'Test desc without name']);
    }

    /** @test */
    public function test_peut_afficher_article()
    {
        $this->authenticateUser();
        $article = Article::factory()->create([
            'name' => 'Specific Article Name',
            'description' => 'Specific Article Description'
        ]);

        $response = $this->get(route('articles.show', $article));

        $response->assertStatus(200);
        $response->assertSee($article->name);
        $response->assertSee($article->description);
    }

    /** @test */
    public function test_utilisateur_authentifie_peut_modifier_article()
    {
        $user = $this->authenticateUser();
        $category = Categorie::factory()->create();
        $fournisseur = Fournisseur::factory()->create();
        $emplacement = Emplacement::factory()->create();

        $article = Article::factory()->create([
            'created_by' => $user->id,
            'category_id' => $category->id,
            'fournisseur_id' => $fournisseur->id,
            'emplacement_id' => $emplacement->id,
        ]);

        $newCategory = Categorie::factory()->create();
        $newFournisseur = Fournisseur::factory()->create();
        $newEmplacement = Emplacement::factory()->create();

        $updatedData = [
            'name' => 'Updated Article Name',
            'description' => 'Updated description for article.',
            'prix' => 150.75,
            'quantite' => 5,
            'category_id' => $newCategory->id,
            'fournisseur_id' => $newFournisseur->id,
            'emplacement_id' => $newEmplacement->id,
        ];

        $response = $this->put(route('articles.update', $article), $updatedData);

        $response->assertRedirect(route('articles.index'));
        $response->assertSessionHas('success', 'Article mis à jour avec succès.');
        $this->assertDatabaseHas('articles', array_merge(['id' => $article->id], $updatedData));
    }

    /** @test */
    public function test_utilisateur_authentifie_peut_supprimer_article()
    {
        $user = $this->authenticateUser();
        $article = Article::factory()->create(['created_by' => $user->id]);

        $response = $this->delete(route('articles.destroy', $article));

        $response->assertRedirect(route('articles.index'));
        $response->assertSessionHas('success', 'Article supprimé avec succès.');
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
