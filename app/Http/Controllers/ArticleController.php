<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth; // Added for auth()->id()

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $search = $request->input('search');
            // Assuming 'searchByText' is a local scope on the Article model
            $articles = Article::when($search, function($query, $term) {
                                    return $query->where('name', 'like', "%{$term}%") // Example search logic
                                                 ->orWhere('description', 'like', "%{$term}%");
                                })
                                ->latest()
                                ->paginate(15);
            return response()->json($articles);
        } catch (\Exception $e) {
            Log::error('Error fetching articles: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching articles', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $userId = Auth::id();

            // Note: 'created_by' => $userId might fail if $userId is null and DB column doesn't allow it.
            // This will be properly handled when authentication is set up.
            $article = Article::create($validatedData + ['created_by' => $userId]);

            return response()->json($article, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error creating article: ' . $e->getMessage());
            return response()->json(['message' => 'Error creating article: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $article = Article::findOrFail($id);
            return response()->json($article);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Article not found'], 404);
        } catch (\Exception $e) {
            Log::error("Error fetching article {$id}: " . $e->getMessage());
            return response()->json(['message' => 'Error fetching article', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, string $id): JsonResponse
    {
        try {
            $article = Article::findOrFail($id);
            // TODO: Add authorization check: e.g., if (Auth::id() !== $article->created_by && $article->created_by !== null) { abort(403, 'Unauthorized'); }
            $article->update($request->validated());
            return response()->json($article);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Article not found'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("Error updating article {$id}: " . $e->getMessage());
            return response()->json(['message' => 'Error updating article', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $article = Article::findOrFail($id);
            // TODO: Add authorization check here
            $article->delete();
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Article not found'], 404);
        } catch (\Exception $e) {
            Log::error("Error deleting article {$id}: " . $e->getMessage());
            return response()->json(['message' => 'Error deleting article', 'error' => $e->getMessage()], 500);
        }
    }
}
