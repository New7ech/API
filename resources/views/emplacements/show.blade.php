@extends('layouts.app')

@section('contenus')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Détails de l'emplacement : {{ $emplacement->name }}</h1>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Nom de l'emplacement :</dt>
                <dd class="col-sm-9">{{ $emplacement->name }}</dd>

                <dt class="col-sm-3">Description :</dt>
                <dd class="col-sm-9">{{ $emplacement->description ?: 'N/A' }}</dd>

                <dt class="col-sm-3">Créé le :</dt>
                <dd class="col-sm-9">{{ $emplacement->created_at->format('d/m/Y H:i') }}</dd>

                <dt class="col-sm-3">Modifié le :</dt>
                <dd class="col-sm-9">{{ $emplacement->updated_at->format('d/m/Y H:i') }}</dd>
            </dl>
            <hr>
            <div class="mt-4">
                <a href="{{ route('emplacements.edit', $emplacement->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <form action="{{ route('emplacements.destroy', $emplacement->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet emplacement ? Ceci pourrait affecter les articles associés.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </form>
                <a href="{{ route('emplacements.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
