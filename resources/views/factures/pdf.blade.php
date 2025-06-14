<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture Professionnelle</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --light-gray: #f8f9fa;
            --medium-gray: #eaeaea;
            --dark-gray: #7f8c8d;
            --success-color: #27ae60;
            --warning-color: #f39c12;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Montserrat', sans-serif;
            color: #333;
            font-size: 14px;
            background: #f9f9f9;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            background: #fff;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--medium-gray);
        }
        .header-strip {
            background: var(--primary-color);
            padding: 15px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-weight: 700;
            font-size: 18px;
            color: white;
            letter-spacing: 0.5px;
        }
        .invoice-number {
            background: rgba(255,255,255,0.15);
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 13px;
        }
        .facture-header {
            padding: 25px 30px 15px;
            border-bottom: 1px solid var(--medium-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .facture-header h1 {
            font-size: 24px;
            margin: 0;
            color: var(--primary-color);
            font-weight: 700;
        }
        .facture-header .date {
            font-size: 14px;
            color: var(--dark-gray);
            background: var(--light-gray);
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
        }
        .info-container {
            display: flex;
            padding: 25px 30px 15px;
            gap: 30px;
        }
        .company-info, .client-info {
            flex: 1;
            background: var(--light-gray);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }
        .company-info h3, .client-info h3 {
            margin-top: 0;
            color: var(--primary-color);
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed var(--medium-gray);
        }
        .info-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 8px 15px;
        }
        .info-label {
            font-weight: 600;
            color: var(--dark-gray);
        }
        .info-value {
            font-weight: 500;
        }
        hr {
            border: none;
            border-top: 1px dashed var(--medium-gray);
            margin: 15px 30px;
        }
        .facture-meta {
            display: flex;
            justify-content: space-between;
            margin: 0 30px 20px;
            padding: 15px;
            background: rgba(52, 152, 219, 0.05);
            border-radius: 8px;
            border-left: 3px solid var(--secondary-color);
        }
        .facture-meta p {
            margin: 0;
            font-size: 14px;
            font-weight: 500;
        }
        .status {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status.paid {
            background: rgba(39, 174, 96, 0.15);
            color: var(--success-color);
        }
        .status.pending {
            background: rgba(243, 156, 18, 0.15);
            color: var(--warning-color);
        }
        h2 {
            text-align: center;
            color: var(--primary-color);
            margin: 15px 0 20px;
            font-size: 18px;
            font-weight: 600;
            padding: 0 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            padding: 0 30px;
        }
        table thead {
            background: var(--primary-color);
            color: #fff;
        }
        table th {
            font-weight: 500;
            padding: 12px 15px;
            text-align: left;
        }
        table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--medium-gray);
        }
        tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.03);
        }
        .total-section {
            margin: 20px 30px 0;
            text-align: right;
        }
        .total-section table {
            width: auto;
            margin-left: auto;
            background: var(--light-gray);
            border-radius: 8px;
            padding: 0;
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .total-section td {
            padding: 10px 20px;
            border: none;
        }
        .total-section tr:last-child td {
            font-weight: 700;
            font-size: 16px;
            background: rgba(52, 152, 219, 0.1);
        }
        .footer {
            text-align: center;
            margin: 30px 30px 20px;
            padding-top: 20px;
            font-size: 13px;
            color: var(--dark-gray);
            font-style: italic;
            border-top: 1px solid var(--medium-gray);
        }
        @media (max-width: 650px) {
            .info-container { flex-direction: column; gap: 20px; }
            .facture-meta { flex-direction: column; gap: 10px; }
            .header-strip { flex-direction: column; gap: 10px; text-align: center; }
        }
        @media print {
            body { background: #fff !important; }
            .container { box-shadow: none !important; border: none !important; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-strip">
            <div class="logo">ENTREPRISE XYZ</div>
            <div class="invoice-number">FACTURE N° {{ $facture->numero ?? $facture->id }}</div>
        </div>
        <div class="facture-header">
            <h1>Facture</h1>
            <span class="date">Émise le : {{ $facture->date_facture ? \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') : 'N/A' }}</span>
        </div>
        <div class="info-container">
            <div class="company-info">
                <h3>Votre Entreprise</h3>
                <div class="info-grid">
                    <span class="info-label">Nom :</span>
                    <span class="info-value">Entreprise XYZ</span>
                    <span class="info-label">Adresse :</span>
                    <span class="info-value">123 Rue de l'Exemple, Ville</span>
                    <span class="info-label">Téléphone :</span>
                    <span class="info-value">+226 XX XX XX XX</span>
                    <span class="info-label">Email :</span>
                    <span class="info-value">contact@gmail.com</span>
                </div>
            </div>
            <div class="client-info">
                <h3>Client</h3>
                <div class="info-grid">
                    <span class="info-label">Nom :</span>
                    <span class="info-value">{{ $facture->client_nom ?? 'N/A' }} {{ $facture->client_prenom ?? '' }}</span>
                    <span class="info-label">Adresse :</span>
                    <span class="info-value">{{ $facture->client_adresse ?? 'N/A' }}</span>
                    <span class="info-label">Téléphone :</span>
                    <span class="info-value">{{ $facture->client_telephone ?? 'N/A' }}</span>
                    <span class="info-label">Email :</span>
                    <span class="info-value">{{ $facture->client_email ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
        <hr>
        <div class="facture-meta">
            <p><strong>Numéro de Facture :</strong> {{ $facture->numero ?? $facture->id }}</p>
            <p>
                <strong>Statut de Paiement :</strong>
                @php
                    $statut = strtolower($facture->statut_paiement ?? '');
                @endphp
                <span class="status {{ $statut === 'payée' ? 'paid' : 'pending' }}">
                    {{ ucfirst($facture->statut_paiement ?? 'N/A') }}
                </span>
            </p>
            @if($facture->mode_paiement)
                <p><strong>Mode de Paiement :</strong> {{ $facture->mode_paiement }}</p>
            @endif
        </div>
        <h2>Détails de la Facture</h2>
        <table>
            <thead>
                <tr>
                    <th>Article</th>
                    <th>Quantité</th>
                    <th>Prix unitaire (FCFA)</th>
                    <th>Montant HT (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($details as $detail)
                    <tr>
                        <td>{{ $detail['article']->name ?? 'N/A' }}</td>
                        <td>{{ $detail['quantity'] }}</td>
                        <td>{{ number_format($detail['prix_unitaire'], 0, ',', ' ') }} FCFA</td>
                        <td>{{ number_format($detail['montant_ht'], 0, ',', ' ') }} FCFA</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">Aucun article sur cette facture.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="total-section">
            <table>
                <tr>
                    <td><strong>Montant HT Total :</strong></td>
                    <td>{{ number_format($facture->montant_ht, 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr>
                    <td><strong>TVA ({{ $facture->tva }}%) :</strong></td>
                    <td>{{ number_format(($facture->montant_ttc - $facture->montant_ht), 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr>
                    <td><strong>Montant TTC Total :</strong></td>
                    <td><strong>{{ number_format($facture->montant_ttc, 0, ',', ' ') }} FCFA</strong></td>
                </tr>
            </table>
        </div>
        <div class="footer">
            <p>Merci de votre confiance. Paiement à effectuer dans les 30 jours suivant la réception de cette facture.<br>
            <span style="color:var(--primary-color);font-weight:600;">Entreprise XYZ</span> — L’élégance au service de votre gestion.</p>
        </div>
    </div>
</body>
</html>
