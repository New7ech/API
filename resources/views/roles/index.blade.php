@extends('layouts.app')
@section('contenus')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title mb-0">Liste des Rôles</h1>
        </div>
        <div class="card-body">
            <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i> Créer un rôle
            </a>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nom du Rôle</th>
                            <th>Permissions</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>
                                    @if($role->permissions->isNotEmpty())
                                        @foreach($role->permissions->take(5) as $permission)
                                            <span class="badge bg-secondary">{{ $permission->name }}</span>
                                        @endforeach
                                        @if($role->permissions->count() > 5)
                                            <span class="badge bg-light text-dark">+ {{ $role->permissions->count() - 5 }} autres</span>
                                        @endif
                                    @else
                                        Aucune permission
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?');">
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
                                <td colspan="3" class="text-center">Aucun rôle trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
