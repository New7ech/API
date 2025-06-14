@extends('layouts.app')

@section('contenus')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title mb-0">Liste des factures</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('factures.index') }}" method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher par numéro, client..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </div>
            </form>
            <a href="{{ route('factures.create') }}" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i> Créer une nouvelle facture
            </a>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Montant HT</th>
                            <th>Montant TTC</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($factures as $facture)
                        <tr>
                            <td>{{ $facture->numero ?? $facture->id }}</td>
                            <td>{{ $facture->client_nom ?? 'N/A' }}</td>
                            <td>{{ $facture->date_facture ? \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') : 'N/A' }}</td>
                            <td>{{ number_format($facture->montant_ht, 2, ',', ' ') }} FCFA</td>
                            <td>{{ number_format($facture->montant_ttc, 2, ',', ' ') }} FCFA</td>
                            <td>
                                <span class="badge {{ $facture->statut_paiement == 'payé' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($facture->statut_paiement) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('factures.show', $facture->id) }}" class="btn btn-info btn-sm" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('factures.edit', $facture->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('factures.pdf', $facture->id) }}" class="btn btn-secondary btn-sm" title="Télécharger PDF" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <form action="{{ route('factures.destroy', $facture->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette facture ?');">
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
                            <td colspan="7" class="text-center">Aucune facture trouvée.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($factures->hasPages())
                <div class="mt-3">
                    {{ $factures->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
