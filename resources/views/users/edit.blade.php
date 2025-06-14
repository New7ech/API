@extends('layouts.app')

@section('contenus')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Mettre à jour l'utilisateur</h1>
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

            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h5 class="mb-3">Informations de base</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label required">Nom complet</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required pattern=".{3,50}" title="3 à 50 caractères">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label required">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required placeholder="exemple@domain.com">
                        </div>
                    </div>
                </div>
                 <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="password" id="password" class="form-control">
                            <div class="form-text">Laissez vide si vous ne souhaitez pas changer le mot de passe.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmation du nouveau mot de passe</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                     <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}" pattern="[+0-9\s]{8,20}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="birthdate" class="form-label">Date de naissance</label>
                            <input type="date" name="birthdate" id="birthdate" class="form-control" value="{{ old('birthdate', $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                </div>
                 <div class="mb-3">
                    <label for="address" class="form-label">Adresse complète</label>
                    <textarea name="address" id="address" class="form-control" rows="2">{{ old('address', $user->address) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="photo" class="form-label">Photo de profil</label>
                    <input type="file" name="photo" id="photo" class="form-control" accept="image/jpeg,image/png,image/webp">
                    <div class="form-text">Laissez vide si vous ne souhaitez pas changer la photo. Formats acceptés: JPG, PNG, WEBP (max 2MB)</div>
                    @if($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo de profil actuelle" class="img-thumbnail mt-2" style="max-height: 100px;">
                    @endif
                </div>

                <hr>
                <h5 class="mb-3">Assignation de Compagnie et Rôles</h5>
                    

                    <div class="mb-3">
                        <label class="form-label">Rôles</label>
                        @foreach ($roles as $role)
                            <div class="form-check">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" id="role-{{ $role->id }}" class="form-check-input" {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                <label for="role-{{ $role->id }}" class="form-check-label">{{ $role->name }}</label>
                            </div>
                        @endforeach
                    </div>
                


                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Mettre à jour
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
