@extends('layouts.app')

@section('contenus')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Modifier la facture</h1>
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

            <form action="{{ route('factures.update', $facture->id) }}" method="POST" id="factureForm">
                @csrf
                @method('PUT')

                <div id="articles-container">
                    @foreach($facture->articles as $index => $article)
                    <div class="row mb-3 article-row">
                        <div class="col-md-6">
                            <label class="form-label">Article</label>
                            <select name="articles[{{ $index }}][article_id]" class="form-select article-select" required>
                                <option value="">Sélectionnez un article</option>
                                @foreach($articles as $art)
                                    <option value="{{ $art->id }}" data-prix="{{ $art->prix }}" data-stock="{{ $art->quantite }}" {{ $art->id == $article->id ? 'selected' : '' }}>
                                        {{ $art->name }} ({{ number_format($art->prix, 2, ',', ' ') }} FCFA)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Quantité</label>
                            <input type="number" name="articles[{{ $index }}][quantity]" class="form-control quantity-input" min="1" required value="{{ $article->pivot->quantite }}">
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-article">Supprimer</button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-success" id="addArticleBtn">
                        <i class="fas fa-plus"></i> Ajouter un article
                    </button>
                </div>

                <hr>
                <h5 class="mb-3">Récapitulatif</h5>
                <div class="row bg-light p-3 rounded mb-4">
                    <div class="col-md-4">
                        <p class="mb-0">Montant HT : <strong><span id="montantHT">{{ $facture->montant_ht }}</span> FCFA</strong></p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-0">TVA (18%) : <strong><span id="montantTVA">{{ $facture->montant_ht * 0.18 }}</span> FCFA</strong></p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-0">Montant TTC : <strong><span id="montantTTC">{{ $facture->montant_ttc }}</span> FCFA</strong></p>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="mode_paiement" class="form-label">Mode de paiement</label>
                    <select name="mode_paiement" id="mode_paiement" class="form-select">
                        <option value="">Sélectionnez un mode de paiement</option>
                        <option value="carte" {{ $facture->mode_paiement == 'carte' ? 'selected' : '' }}>Carte</option>
                        <option value="chèque" {{ $facture->mode_paiement == 'chèque' ? 'selected' : '' }}>Chèque</option>
                        <option value="espèces" {{ $facture->mode_paiement == 'espèces' ? 'selected' : '' }}>Espèces</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="statut_paiement" class="form-label">Statut du paiement</label>
                    <select name="statut_paiement" id="statut_paiement" class="form-select" required>
                        <option value="impayé" {{ $facture->statut_paiement == 'impayé' ? 'selected' : '' }}>impayé</option>
                        <option value="payé" {{ $facture->statut_paiement == 'payé' ? 'selected' : '' }}>Payé</option>
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Mettre à jour la facture
                    </button>
                    <a href="{{ route('factures.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> Retour à la liste
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let index = {{ count($facture->articles) }};
    const container = document.getElementById('articles-container');
    const addBtn = document.getElementById('addArticleBtn');

    function calculateTotals() {
        let totalHT = 0;

        document.querySelectorAll('.article-row').forEach(row => {
            const select = row.querySelector('.article-select');
            const qtyInput = row.querySelector('.quantity-input');
            const prix = parseFloat(select.selectedOptions[0]?.dataset.prix || 0);
            const stock = parseInt(select.selectedOptions[0]?.dataset.stock || 0);
            const qty = parseInt(qtyInput.value || 0);

            if (qty > stock) {
                qtyInput.setCustomValidity('Quantité supérieure au stock disponible !');
                qtyInput.reportValidity();
                return;
            } else {
                qtyInput.setCustomValidity('');
            }

            totalHT += prix * qty;
        });

        const tva = totalHT * 0.18;
        const ttc = totalHT + tva;

        document.getElementById('montantHT').textContent = totalHT.toFixed(2);
        document.getElementById('montantTVA').textContent = tva.toFixed(2);
        document.getElementById('montantTTC').textContent = ttc.toFixed(2);
    }

    function addArticleRow() {
        const newRow = document.createElement('div');
        newRow.className = 'row mb-3 article-row';
        newRow.innerHTML = `
            <div class="col-md-6">
                <select name="articles[${index}][article_id]" class="form-select article-select" required>
                    <option value="">Sélectionnez un article</option>
                    @foreach($articles as $article)
                        <option value="{{ $article->id }}" data-prix="{{ $article->prix }}" data-stock="{{ $article->quantite }}">
                            {{ $article->name }} ({{ number_format($article->prix, 2, ',', ' ') }} FCFA)
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="articles[${index}][quantity]" class="form-control quantity-input" min="1" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-article">&times;</button>
            </div>
        `;
        container.appendChild(newRow);
        index++;
        bindEvents();
    }

    function bindEvents() {
        document.querySelectorAll('.article-select, .quantity-input').forEach(el => {
            el.removeEventListener('change', calculateTotals);
            el.removeEventListener('input', calculateTotals);
            el.addEventListener('change', calculateTotals);
            el.addEventListener('input', calculateTotals);
        });

        document.querySelectorAll('.remove-article').forEach(btn => {
            btn.removeEventListener('click', removeRow);
            btn.addEventListener('click', removeRow);
        });

        calculateTotals();
    }

    function removeRow(e) {
        e.target.closest('.article-row').remove();
        calculateTotals();
    }

    addBtn.addEventListener('click', addArticleRow);
    bindEvents();
});
</script>
@endsection
