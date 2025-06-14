@extends('layouts/app')
@section('contenus')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title mb-0">Liste des catégories</h1>
        </div>
        <div class="card-body">
            <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i> Créer une catégorie
            </a>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $categorie)
                            <tr>
                                <td>{{ $categorie->name }}</td>
                                <td>{{ Str::limit($categorie->description, 70) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('categories.edit', $categorie) }}" class="btn btn-warning btn-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('categories.destroy', $categorie) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ? Ceci pourrait affecter les articles associés.');">
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
                                <td colspan="3" class="text-center">Aucune catégorie trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
