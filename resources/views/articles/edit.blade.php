@extends('layouts.app')

@section('contenus')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Modifier l'article</h1>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('articles.update', $article->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nom de l'article</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name', $article->name) }}">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control">{{ old('description', $article->description) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="prix" class="form-label">Prix (FCFA)</label>
                            <input type="number" step="0.01" name="prix" id="prix" class="form-control" required value="{{ old('prix', $article->prix) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="quantite" class="form-label">Quantité</label>
                            <input type="number" name="quantite" id="quantite" class="form-control" required value="{{ old('quantite', $article->quantite) }}">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Catégorie</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- Choisir --</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ old('category_id', $article->category_id) == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="fournisseur_id" class="form-label">Fournisseur</label>
                    <select name="fournisseur_id" class="form-select">
                        <option value="">-- Choisir --</option>
                        @foreach($fournisseurs as $fournisseur)
                            <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id', $article->fournisseur_id) == $fournisseur->id ? 'selected' : '' }}>
                                {{ $fournisseur->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="emplacement_id" class="form-label">Emplacement</label>
                    <select name="emplacement_id" class="form-select">
                        <option value="">-- Choisir --</option>
                        @foreach($emplacements as $emplacement)
                            <option value="{{ $emplacement->id }}" {{ old('emplacement_id', $article->emplacement_id) == $emplacement->id ? 'selected' : '' }}>
                                {{ $emplacement->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Mettre à jour
                    </button>
                    <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> Retour à la liste
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
