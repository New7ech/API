@extends('layouts.app')

@section('contenus')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Détails du fournisseur : {{ $fournisseur->name }}</h1>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Nom du fournisseur :</dt>
                <dd class="col-sm-9">{{ $fournisseur->name }}</dd>

                <dt class="col-sm-3">Nom de l'entreprise :</dt>
                <dd class="col-sm-9">{{ $fournisseur->nom_entreprise ?: 'N/A' }}</dd>

                <dt class="col-sm-3">Description :</dt>
                <dd class="col-sm-9">{{ $fournisseur->description ?: 'N/A' }}</dd>

                <dt class="col-sm-3">Adresse :</dt>
                <dd class="col-sm-9">{{ $fournisseur->adresse ?: 'N/A' }}</dd>

                <dt class="col-sm-3">Téléphone :</dt>
                <dd class="col-sm-9">{{ $fournisseur->telephone ?: 'N/A' }}</dd>

                <dt class="col-sm-3">Email :</dt>
                <dd class="col-sm-9">{{ $fournisseur->email ?: 'N/A' }}</dd>

                <dt class="col-sm-3">Ville :</dt>
                <dd class="col-sm-9">{{ $fournisseur->ville ?: 'N/A' }}</dd>

                <dt class="col-sm-3">Pays :</dt>
                <dd class="col-sm-9">{{ $fournisseur->pays ?: 'N/A' }}</dd>

                <dt class="col-sm-3">Créé le :</dt>
                <dd class="col-sm-9">{{ $fournisseur->created_at->format('d/m/Y H:i') }}</dd>

                <dt class="col-sm-3">Modifié le :</dt>
                <dd class="col-sm-9">{{ $fournisseur->updated_at->format('d/m/Y H:i') }}</dd>
            </dl>
            <hr>
            <div class="mt-4">
                <a href="{{ route('fournisseurs.edit', $fournisseur->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <form action="{{ route('fournisseurs.destroy', $fournisseur->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ? Ceci pourrait affecter les articles associés.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </form>
                <a href="{{ route('fournisseurs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
