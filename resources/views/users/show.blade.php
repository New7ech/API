@extends('layouts.app')

@section('contenus')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Détails de l'utilisateur : {{ $user->name }}</h1>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3 text-center">
                    @if($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo de profil" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar par défaut" width="100">
                    @endif
                </div>
                <div class="col-md-9">
                    <dl class="row">
                        <dt class="col-sm-4">Nom complet :</dt>
                        <dd class="col-sm-8">{{ $user->name }}</dd>

                        <dt class="col-sm-4">Email :</dt>
                        <dd class="col-sm-8">{{ $user->email }}</dd>

                        <dt class="col-sm-4">Téléphone :</dt>
                        <dd class="col-sm-8">{{ $user->phone ?: 'N/A' }}</dd>

                        <dt class="col-sm-4">Date de naissance :</dt>
                        <dd class="col-sm-8">{{ $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('d/m/Y') : 'N/A' }}</dd>

                        <dt class="col-sm-4">Adresse :</dt>
                        <dd class="col-sm-8">{{ $user->address ?: 'N/A' }}</dd>


                        <dt class="col-sm-4">Rôles :</dt>
                        <dd class="col-sm-8">
                            @forelse($user->roles as $role)
                                <span class="badge bg-info">{{ $role->name }}</span>
                            @empty
                                Aucun rôle assigné
                            @endforelse
                        </dd>

                        <dt class="col-sm-4">Statut du compte :</dt>
                        <dd class="col-sm-8">
                            <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->status ? 'Actif' : 'Inactif' }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Créé le :</dt>
                        <dd class="col-sm-8">{{ $user->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-4">Modifié le :</dt>
                        <dd class="col-sm-8">{{ $user->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>
            <hr>
            <div class="mt-4">
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </form>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
