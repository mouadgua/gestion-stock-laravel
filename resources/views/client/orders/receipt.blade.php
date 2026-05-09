<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu #{{ $order->id_commande }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .receipt {
            border: 2px solid #667eea;
            border-radius: 10px;
            padding: 30px;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #667eea;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header .subtitle {
            color: #666;
            font-size: 14px;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-box {
            flex: 1;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
            margin: 0 10px;
        }
        .info-box:first-child {
            margin-left: 0;
        }
        .info-box:last-child {
            margin-right: 0;
        }
        .info-box h3 {
            color: #667eea;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .info-box p {
            font-size: 13px;
            margin: 3px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th {
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 13px;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .items-table .total-row {
            background: #f9fafb;
            font-weight: bold;
        }
        .items-table .total-row td {
            font-size: 16px;
            color: #667eea;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px dashed #ddd;
        }
        .footer p {
            font-size: 12px;
            color: #666;
            margin: 5px 0;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .qr-code img {
            max-width: 120px;
            height: auto;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-en_attente { background: #fef3c7; color: #92400e; }
        .status-expediee { background: #dbeafe; color: #1e40af; }
        .status-livree { background: #d1fae5; color: #065f46; }
        .status-annulee { background: #fee2e2; color: #991b1b; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="background: #667eea; color: white; border: none; padding: 12px 30px; border-radius: 8px; cursor: pointer; font-size: 14px;">
            🖨️ Imprimer le reçu
        </button>
        <a href="{{ route('client.orders.show', $order) }}" style="margin-left: 10px; color: #667eea; text-decoration: none;">Retour</a>
    </div>

    <div class="receipt">
        <div class="header">
            <h1>🛒 Boutique Virtuelle</h1>
            <p class="subtitle">Reçu de commande</p>
        </div>

        <div class="info-section">
            <div class="info-box">
                <h3>📋 Informations</h3>
                <p><strong>N° Commande :</strong> #{{ $order->id_commande }}</p>
                <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($order->date_commande)->format('d/m/Y à H:i') }}</p>
                <p>
                    <strong>Statut :</strong> 
                    <span class="status-badge status-{{ $order->statut }}">
                        {{ ucfirst(str_replace('_', ' ', $order->statut)) }}
                    </span>
                </p>
            </div>
            <div class="info-box">
                <h3>👤 Client</h3>
                <p><strong>{{ $order->user->name }}</strong></p>
                <p>{{ $order->user->email }}</p>
                <p>{{ $order->user->telephone ?? '' }}</p>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Qté</th>
                    <th>Prix unit.</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->nom_produit }}</td>
                        <td>{{ $item->quantite }}</td>
                        <td>{{ number_format($item->sous_total / $item->quantite, 2) }} €</td>
                        <td>{{ number_format($item->sous_total, 2) }} €</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">TOTAL</td>
                    <td>{{ number_format($order->total, 2) }} €</td>
                </tr>
            </tbody>
        </table>

        <div class="info-section">
            <div class="info-box">
                <h3>📍 Livraison</h3>
                <p>{{ $order->adresse_livraison ?? $order->user->adresse ?? 'Non renseignée' }}</p>
            </div>
            <div class="info-box">
                <h3>💳 Paiement</h3>
                <p><strong>Mode :</strong> À la livraison</p>
                <p><strong>Statut :</strong> <span style="color: green;">Payé</span></p>
            </div>
        </div>

        <div class="footer">
            <p><strong>Merci pour votre achat !</strong></p>
            <p>Pour toute question, contactez-nous à support@boutique.com</p>
            <p style="margin-top: 15px; font-size: 11px; color: #999;">
                Ce document fait office de justificatif d'achat. Conservez-le précieusement.
            </p>
        </div>
    </div>
</body>
</html>