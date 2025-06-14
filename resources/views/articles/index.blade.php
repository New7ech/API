@extends('layouts.app')

@section('contenus')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title mb-0">Liste des articles</h1>
        </div>
        <div class="card-body">
            <a href="{{ route('articles.create') }}" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i> Créer un nouvel article
            </a>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                        <tr>
                            <td>{{ $article->name }}</td>
                            <td>{{ Str::limit($article->description, 50) }}</td>
                            <td>{{ number_format($article->prix, 2, ',', ' ') }} FCFA</td>
                            <td>{{ $article->quantite }}</td>
                            <td>
                                <a href="{{ route('articles.show', $article->id) }}" class="btn btn-info btn-sm" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('articles.destroy', $article->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Aucun article trouvé.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
