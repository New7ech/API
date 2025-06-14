@extends('layouts/app')
@section('contenus')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title mb-0">Liste des fournisseurs</h1>
        </div>
        <div class="card-body">
            <a href="{{ route('fournisseurs.create') }}" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i> Créer un fournisseur
            </a>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Nom de l'entreprise</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fournisseurs as $fournisseur)
                            <tr>
                                <td>{{ $fournisseur->name }}</td>
                                <td>{{ Str::limit($fournisseur->description, 50) }}</td>
                                <td>{{ $fournisseur->nom_entreprise }}</td>
                                <td class="text-end">
                                    <a href="{{ route('fournisseurs.show', $fournisseur->id) }}" class="btn btn-info btn-sm" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('fournisseurs.edit', $fournisseur) }}" class="btn btn-warning btn-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('fournisseurs.destroy', $fournisseur) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ? Ceci pourrait affecter les articles associés.');">
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
                                <td colspan="4" class="text-center">Aucun fournisseur trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
