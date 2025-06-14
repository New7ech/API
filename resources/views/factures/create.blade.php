@extends('layouts.app')

@section('contenus')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Créer une nouvelle facture</h1>
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

            <form action="{{ route('factures.store') }}" method="POST" id="factureForm">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="client_nom" class="form-label">Nom du client <span class="text-danger">*</span></label>
                        <input type="text" name="client_nom" id="client_nom" class="form-control" required value="{{ old('client_nom') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="client_prenom" class="form-label">Prénom du client</label>
                        <input type="text" name="client_prenom" id="client_prenom" class="form-control" value="{{ old('client_prenom') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="client_telephone" class="form-label">Téléphone</label>
                        <input type="text" name="client_telephone" id="client_telephone" class="form-control" value="{{ old('client_telephone') }}">
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-8">
                        <label for="client_adresse" class="form-label">Adresse</label>
                        <input type="text" name="client_adresse" id="client_adresse" class="form-control" value="{{ old('client_adresse') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="client_email" class="form-label">Email</label>
                        <input type="email" name="client_email" id="client_email" class="form-control" value="{{ old('client_email') }}">
                    </div>
                </div>

                <div id="articles-container">
                    <div class="row mb-3 article-row">
                        <div class="col-md-6">
                            <label class="form-label">Article</label>
                            <select name="articles[0][article_id]" class="form-select article-select" required>
                                <option value="">Sélectionnez un article</option>
                                @foreach($articles as $article)
                                    <option value="{{ $article->id }}" data-prix="{{ $article->prix }}" data-stock="{{ $article->quantite }}">
                                        {{ $article->name }} ({{ number_format($article->prix, 2, ',', ' ') }} FCFA)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Quantité</label>
                            <input type="number" name="articles[0][quantity]" class="form-control quantity-input" min="1" required>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-article" disabled>&times;</button>
                        </div>
                    </div>
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
                        <p class="mb-0">Montant HT : <strong><span id="montantHT">0.00</span> FCFA</strong></p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-0">TVA (18%) : <strong><span id="montantTVA">0.00</span> FCFA</strong></p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-0">Montant TTC : <strong><span id="montantTTC">0.00</span> FCFA</strong></p>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="mode_paiement" class="form-label">Mode de paiement</label>
                    <select name="mode_paiement" id="mode_paiement" class="form-select">
                        <option value="">Sélectionnez un mode de paiement</option>
                        <option value="carte">Carte</option>
                        <option value="chèque">Chèque</option>
                        <option value="espèces">Espèces</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="statut_paiement" class="form-label">Statut du paiement</label>
                    <select name="statut_paiement" id="statut_paiement" class="form-select" required>
                        <option value="impayé">impayé</option>
                        <option value="payé">Payé</option>
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                        <i class="fas fa-file-invoice"></i> Générer la facture
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
    let index = 1;
    const container = document.getElementById('articles-container');
    const addBtn = document.getElementById('addArticleBtn');
    const submitBtn = document.getElementById('submitBtn');

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
                submitBtn.disabled = true;
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

        submitBtn.disabled = totalHT <= 0;
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
