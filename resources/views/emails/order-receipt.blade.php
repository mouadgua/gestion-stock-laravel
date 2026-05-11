<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu #{{ $order->id_commande }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #f8fafc; margin: 0; padding: 0; color: #1e293b; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border: 1px solid #e2e8f0; }
        .header { background: #0f172a; padding: 32px 40px; }
        .header h1 { color: #ffffff; font-size: 28px; font-weight: 900; letter-spacing: -1px; margin: 0; text-transform: uppercase; }
        .header p { color: #94a3b8; font-size: 12px; text-transform: uppercase; letter-spacing: 2px; margin: 4px 0 0; }
        .body { padding: 40px; }
        .greeting { font-size: 16px; font-weight: 700; margin-bottom: 24px; }
        .order-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 32px; }
        .order-box h2 { font-size: 12px; text-transform: uppercase; letter-spacing: 2px; color: #64748b; margin: 0 0 16px; }
        .order-meta { display: flex; gap: 32px; flex-wrap: wrap; }
        .order-meta div { }
        .order-meta label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; display: block; margin-bottom: 4px; }
        .order-meta span { font-size: 14px; font-weight: 700; color: #0f172a; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th { text-align: left; padding: 8px 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; border-bottom: 2px solid #0f172a; }
        td { padding: 14px 12px; font-size: 14px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        .total-row td { font-weight: 900; font-size: 16px; border-bottom: none; padding-top: 20px; }
        .badge { display: inline-block; padding: 3px 10px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .badge-cod { background: #ecfdf5; color: #065f46; }
        .badge-paypal { background: #eff6ff; color: #1e40af; }
        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px 40px; text-align: center; }
        .footer p { font-size: 12px; color: #94a3b8; margin: 4px 0; text-transform: uppercase; letter-spacing: 1px; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>The Vault.</h1>
        <p>Confirmation de commande</p>
    </div>

    <div class="body">
        <p class="greeting">Bonjour {{ $order->user->name }},</p>
        <p style="color:#475569;font-size:14px;line-height:1.6;">
            Merci pour votre commande. Nous l'avons bien reçue et elle est en cours de traitement.
        </p>

        <div class="order-box">
            <h2>Détails de la commande</h2>
            <div class="order-meta">
                <div>
                    <label>Numéro</label>
                    <span>#{{ $order->id_commande }}</span>
                </div>
                <div>
                    <label>Date</label>
                    <span>{{ $order->created_at->format('d/m/Y') }}</span>
                </div>
                <div>
                    <label>Paiement</label>
                    <span class="badge {{ $order->mode_paiement === 'paypal' ? 'badge-paypal' : 'badge-cod' }}">
                        {{ $order->mode_paiement === 'paypal' ? 'PayPal' : 'À la livraison' }}
                    </span>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Article</th>
                    <th style="text-align:center;">Qté</th>
                    <th style="text-align:right;">Prix unitaire</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td style="font-weight:700;">{{ $item->product->nom_produit ?? 'Produit supprimé' }}</td>
                    <td style="text-align:center;">{{ $item->quantite }}</td>
                    <td style="text-align:right;">{{ number_format($item->prix_unitaire, 2) }} DH</td>
                    <td style="text-align:right;font-weight:700;">{{ number_format($item->sous_total, 2) }} DH</td>
                </tr>
                @endforeach
                @if(($order->discount ?? 0) > 0)
                <tr>
                    <td colspan="3" style="text-align:right;color:#059669;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">Code promo ({{ $order->promo_code }})</td>
                    <td style="text-align:right;color:#059669;font-weight:700;">-{{ number_format($order->discount, 2) }} DH</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td colspan="3" style="text-align:right;color:#64748b;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">Total</td>
                    <td style="text-align:right;">{{ number_format($order->total, 2) }} DH</td>
                </tr>
            </tbody>
        </table>

        <div style="background:#f8fafc;border:1px solid #e2e8f0;padding:20px 24px;margin-bottom:24px;">
            <p style="font-size:12px;text-transform:uppercase;letter-spacing:1px;color:#64748b;margin:0 0 10px;">Adresse de livraison</p>
            <p style="font-size:14px;font-weight:700;margin:0;">{{ $order->adresse_livraison }}</p>
            <p style="font-size:13px;color:#475569;margin:4px 0 0;">Tél : {{ $order->telephone_livraison }}</p>
        </div>

        <p style="font-size:13px;color:#475569;line-height:1.6;">
            Pour toute question, répondez à cet email ou contactez notre service client.
        </p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} The Vault — Tous droits réservés</p>
        <p>Maroc — thevault.maroc@gmail.com</p>
    </div>
</div>
</body>
</html>
