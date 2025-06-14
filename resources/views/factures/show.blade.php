@extends('layouts.app')

@section('contenus')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">Détails de la Facture #{{ $facture->numero ?? $facture->id }}</h1>
            <a href="{{ route('factures.pdf', $facture->id) }}" class="btn btn-light btn-sm" target="_blank">
                <i class="fas fa-file-pdf"></i> Télécharger PDF
            </a>
        </div>
        <div class="card-body">
            <h5 class="card-title mb-3">Informations Client</h5>
            <dl class="row">
                <dt class="col-sm-3">Nom du client :</dt>
                <dd class="col-sm-9">{{ $facture->client_nom ?: 'N/A' }}</dd>

                <dt class="col-sm-3">Prénom du client :</dt>
                <dd class="col-sm-9">{{ $facture->client_prenom ?: 'N/A' }}</dd>

                <dt class="col-sm-3">Téléphone :</dt>
                <dd class="col-sm-9">{{ $facture->client_telephone ?: 'N/A' }}</dd>

                <dt class="col-sm-3">Adresse :</dt>
                <dd class="col-sm-9">{{ $facture->client_adresse ?: 'N/A' }}</dd>

                <dt class="col-sm-3">Email :</dt>
                <dd class="col-sm-9">{{ $facture->client_email ?: 'N/A' }}</dd>
            </dl>

            <hr>
            <h5 class="card-title mt-4 mb-3">Détails de la Facture</h5>
            <dl class="row">
                <dt class="col-sm-3">Date de facturation :</dt>
                <dd class="col-sm-9">{{ $facture->date_facture ? \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') : 'N/A' }}</dd>

                <dt class="col-sm-3">Montant HT :</dt>
                <dd class="col-sm-9">{{ number_format($facture->montant_ht, 2, ',', ' ') }} FCFA</dd>

                <dt class="col-sm-3">TVA ({{ $facture->tva ?? 18 }}%) :</dt>
                <dd class="col-sm-9">{{ number_format($facture->montant_ht * ($facture->tva ?? 18) / 100, 2, ',', ' ') }} FCFA</dd>

                <dt class="col-sm-3">Montant TTC :</dt>
                <dd class="col-sm-9"><strong>{{ number_format($facture->montant_ttc, 2, ',', ' ') }} FCFA</strong></dd>

                <dt class="col-sm-3">Mode de paiement :</dt>
                <dd class="col-sm-9">{{ ucfirst($facture->mode_paiement) ?: 'N/A' }}</dd>

                <dt class="col-sm-3">Statut du paiement :</dt>
                <dd class="col-sm-9">
                    <span class="badge {{ $facture->statut_paiement == 'payé' ? 'bg-success' : 'bg-danger' }}">
                        {{ ucfirst($facture->statut_paiement) }}
                    </span>
                </dd>

                <dt class="col-sm-3">Date de paiement :</dt>
                <dd class="col-sm-9">{{ $facture->date_paiement ? \Carbon\Carbon::parse($facture->date_paiement)->format('d/m/Y') : 'N/A' }}</dd>
            </dl>

            <hr>
            <h5 class="card-title mt-4 mb-3">Articles</h5>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Article</th>
                            <th class="text-end">Quantité</th>
                            <th class="text-end">Prix Unitaire HT</th>
                            <th class="text-end">Montant HT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facture->articles as $article)
                        <tr>
                            <td>{{ $article->name }}</td>
                            <td class="text-end">{{ $article->pivot->quantite }}</td>
                            <td class="text-end">{{ number_format($article->pivot->prix_unitaire, 2, ',', ' ') }} FCFA</td>
                            <td class="text-end">{{ number_format($article->pivot->quantite * $article->pivot->prix_unitaire, 2, ',', ' ') }} FCFA</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <a href="{{ route('factures.edit', $facture->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <form action="{{ route('factures.destroy', $facture->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette facture ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </form>
                <a href="{{ route('factures.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
