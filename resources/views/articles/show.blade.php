@extends('layouts.app')

@section('contenus')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h1>{{ $article->name }}</h1>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Description :</dt>
                <dd class="col-sm-9">{{ $article->description ?: 'N/A' }}</dd>

                <dt class="col-sm-3">Prix :</dt>
                <dd class="col-sm-9">{{ number_format($article->prix, 2, ',', ' ') }} FCFA</dd>

                <dt class="col-sm-3">Quantité en stock :</dt>
                <dd class="col-sm-9">{{ $article->quantite }}</dd>

                <dt class="col-sm-3">Catégorie :</dt>
                <dd class="col-sm-9">{{ $article->categorie->name ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Fournisseur :</dt>
                <dd class="col-sm-9">{{ $article->fournisseur->name ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Emplacement :</dt>
                <dd class="col-sm-9">{{ $article->emplacement->name ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Créé par :</dt>
                <dd class="col-sm-9">{{ $article->user->name ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Créé le :</dt>
                <dd class="col-sm-9">{{ $article->created_at->format('d/m/Y H:i') }}</dd>

                <dt class="col-sm-3">Modifié le :</dt>
                <dd class="col-sm-9">{{ $article->updated_at->format('d/m/Y H:i') }}</dd>
            </dl>
            <hr>
            <div class="mt-4">
                <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <form action="{{ route('articles.destroy', $article->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </form>
                <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
