@extends('layouts/app')
@section('contenus')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title mb-0">Liste des emplacements</h1>
        </div>
        <div class="card-body">
            <a href="{{ route('emplacements.create') }}" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i> Créer un emplacement
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
                        @forelse ($emplacements as $emplacement)
                            <tr>
                                <td>{{ $emplacement->name }}</td>
                                <td>{{ Str::limit($emplacement->description, 70) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('emplacements.show', $emplacement->id) }}" class="btn btn-info btn-sm" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('emplacements.edit', $emplacement) }}" class="btn btn-warning btn-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('emplacements.destroy', $emplacement) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet emplacement ? Ceci pourrait affecter les articles associés.');">
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
                                <td colspan="3" class="text-center">Aucun emplacement trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
