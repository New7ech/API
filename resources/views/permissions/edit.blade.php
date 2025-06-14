@extends('layouts.app')

@section('contenus')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Modifier la permission</h1>
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

            <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nom de la permission</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $permission->name) }}" required placeholder="ex: articles-creer">
                    <div class="form-text">Utilisez un format comme `nomdelentite-action` (par exemple, `articles-lire`, `utilisateurs-modifier`).</div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Mettre à jour
                    </button>
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> Retour à la liste
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
