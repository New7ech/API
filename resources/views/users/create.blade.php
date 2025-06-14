@extends('layouts.app')

@section('contenus')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Créer un nouvel utilisateur</h1>
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

            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h5 class="mb-3">Informations de base</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label required">Nom complet</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required pattern=".{3,50}" title="3 à 50 caractères">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label required">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required placeholder="exemple@domain.com">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email_confirmation" class="form-label required">Confirmation de l'Email</label>
                            <input type="email" name="email_confirmation" id="email_confirmation" class="form-control" required placeholder="Confirmez l'email">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label required">Téléphone</label>
                            <input type="tel" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" required pattern="[+0-9\s]{8,20}">
                        </div>
                    </div>
                </div>
                 <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label required">Mot de passe</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label required">Confirmation du mot de passe</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="birthdate" class="form-label">Date de naissance</label>
                            <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate') }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo de profil</label>
                            <input type="file" name="photo" id="photo" class="form-control" accept="image/jpeg,image/png,image/webp">
                            <div class="form-text">Formats acceptés: JPG, PNG, WEBP (max 2MB)</div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Adresse complète</label>
                    <textarea name="address" id="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                </div>

                <hr>
                <h5 class="mb-3">Rôles et Permissions</h5>
                 <div class="mb-3">
                    <label for="role_id" class="form-label required">Rôle principal</label>
                    <select name="role_id" id="role_id" class="form-select" required>
                        <option value="">Sélectionnez un rôle</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- Placeholder for more granular permissions if needed in future --}}
                {{-- <div class="mb-3">
                    <label class="form-label">Accès aux modules (Exemple)</label>
                    <div class="row row-cols-2 row-cols-md-3 g-3">
                        @foreach(['articles', 'factures', 'clients'] as $module)
                            <div class="col">
                                <div class="form-check card card-body p-2">
                                    <input type="checkbox" name="module_access[]" value="{{ $module }}" class="form-check-input" id="module-{{ $module }}">
                                    <label class="form-check-label" for="module-{{ $module }}">
                                        {{ ucfirst($module) }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div> --}}

                <hr>
                <h5 class="mb-3">Préférences et Options</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="locale" class="form-label">Langue par défaut</label>
                            <select name="locale" id="locale" class="form-select">
                                <option value="fr" {{ old('locale', 'fr') == 'fr' ? 'selected' : '' }}>Français</option>
                                <option value="en" {{ old('locale') == 'en' ? 'selected' : '' }}>English</option>
                                <option value="es" {{ old('locale') == 'es' ? 'selected' : '' }}>Español</option>
                            </select>
                        </div>
                         <div class="mb-3">
                            <label for="preferences" class="form-label">Préférences utilisateur (JSON)</label>
                            <textarea name="preferences" id="preferences" class="form-control" rows="2" placeholder='{"theme": "clair", "items_par_page": 50}'>{{ old('preferences') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="currency" class="form-label">Devise par défaut</label>
                            <select name="currency" id="currency" class="form-select">
                                <option value="XOF" {{ old('currency', 'XOF') == 'XOF' ? 'selected' : '' }}>XOF (Franc CFA)</option>
                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD (Dollar US)</option>
                            </select>
                        </div>
                        <div class="mb-3 pt-3">
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" name="status" id="status" class="form-check-input" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                                <label for="status" class="form-check-label">Compte actif</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" name="is_admin" id="is_admin" class="form-check-input" value="1" {{ old('is_admin') ? 'checked' : '' }}>
                                <label for="is_admin" class="form-check-label">Accès administrateur</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" name="notifications_enabled" id="notifications_enabled" class="form-check-input" value="1" {{ old('notifications_enabled', 1) ? 'checked' : '' }}>
                                <label for="notifications_enabled" class="form-check-label">Activer les notifications</label>
                            </div>
                            <div class="form-check form-switch">
                                <input type="checkbox" name="two_factor_enabled" id="two_factor_enabled" class="form-check-input" value="1" {{ old('two_factor_enabled') ? 'checked' : '' }}>
                                <label for="two_factor_enabled" class="form-check-label">Authentification à deux facteurs</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
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
