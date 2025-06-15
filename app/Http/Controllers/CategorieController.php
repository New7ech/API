<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategorieRequest;
use App\Http\Requests\UpdateCategorieRequest;
use App\Models\Categorie;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('categories.index', [
            'categories' => Categorie::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategorieRequest $request)
    {
        $request->validate(['name' => 'required|unique:categories',
            'description' => 'nullable|string|max:255', // Validation pour la description
        ]);

        Categorie::create(['name' => $request->name,
            'description' => $request->description]);

        return redirect()->route('categories.index')
            ->with('success', 'categorie created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categorie $categorie)
    {
        return view('categories.show', compact('categorie'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categorie $categorie)
    {
        return view('categories.edit', compact('categorie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategorieRequest $request, Categorie $categorie)
    {
        $request->validate(['name' => 'required|unique:categories,name,' . $categorie->id,
            'description' => 'nullable|string|max:255', // Validation pour la description
        ]);

        $categorie->update(['name' => $request->name,
            'description' => $request->description]);

        return redirect()->route('categories.index')
            ->with('success', 'Categorie updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorie $categorie)
    {
        $categorie->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Categorie deleted successfully.');
    }
}
