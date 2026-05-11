<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu #{{ $order->id_commande }} - The Vault</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;700;900&display=swap');
        * { font-family: 'Inter', sans-serif; }
        
        @media print {
            body { background: white !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .print-area { box-shadow: none !important; border: none !important; max-width: 100% !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen p-4 md:p-8 flex flex-col items-center">
    
    <div class="no-print w-full max-w-3xl flex justify-between items-center mb-8">
        <a href="{{ route('client.orders.show', $order) }}" class="text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-slate-900 transition-colors inline-flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
        <button onclick="window.print()" class="bg-slate-900 text-white font-black uppercase tracking-widest px-6 py-3 hover:bg-slate-800 transition-colors inline-flex items-center gap-2">
            <i class="fas fa-print"></i> Imprimer
        </button>
    </div>

    <div class="print-area w-full max-w-3xl bg-white border border-slate-200 shadow-2xl p-10 md:p-16">
        
        <div class="flex justify-between items-end border-b-4 border-slate-900 pb-8 mb-10">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase leading-none">THE VAULT.</h1>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2">Reçu d'acquisition sécurisée</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Document N°</p>
                <p class="text-2xl font-black text-slate-900 tracking-tight">#{{ $order->id_commande }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12 bg-slate-50 p-6 border border-slate-200">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date</p>
                <p class="text-sm font-black text-slate-900">{{ $order->created_at->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Heure</p>
                <p class="text-sm font-black text-slate-900">{{ $order->created_at->format('H:i') }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Membre</p>
                <p class="text-sm font-black text-slate-900 uppercase">{{ auth()->user()->name }}</p>
            </div>
            <div class="text-left md:text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Statut</p>
                <p class="text-sm font-black text-slate-900 uppercase">{{ str_replace('_', ' ', $order->statut) }}</p>
            </div>
        </div>

        <table class="w-full mb-12">
            <thead>
                <tr class="border-b-2 border-slate-900">
                    <th class="py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Désignation</th>
                    <th class="py-4 text-center text-xs font-bold text-slate-400 uppercase tracking-widest">Qté</th>
                    <th class="py-4 text-right text-xs font-bold text-slate-400 uppercase tracking-widest">Prix unitaire</th>
                    <th class="py-4 text-right text-xs font-bold text-slate-400 uppercase tracking-widest">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr class="border-b border-slate-200">
                    <td class="py-6">
                        <p class="font-black text-slate-900 uppercase text-sm">{{ $item->product->nom_produit ?? 'Produit supprimé' }}</p>
                    </td>
                    <td class="py-6 text-center text-sm font-bold text-slate-500">{{ $item->quantite }}</td>
                    <td class="py-6 text-right text-sm font-bold text-slate-700 whitespace-nowrap">{{ number_format($item->prix_unitaire, 2) }} DH</td>
                    <td class="py-6 text-right font-black text-slate-900 whitespace-nowrap text-sm">{{ number_format($item->sous_total, 2) }} DH</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $itemsTotal = $order->items->sum('sous_total');
            $promoDiscount = $order->discount ?? 0;
        @endphp

        <div class="flex justify-end border-t-4 border-slate-900 pt-6">
            <div class="w-full md:w-1/2">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Sous-total articles</span>
                    <span class="text-sm font-black text-slate-900">{{ number_format($itemsTotal, 2) }} DH</span>
                </div>
                @if($promoDiscount > 0)
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Promo {{ $order->promo_code }}</span>
                        <span class="text-sm font-black text-emerald-600">-{{ number_format($promoDiscount, 2) }} DH</span>
                    </div>
                @endif
                <div class="flex justify-between items-center mb-6 border-b border-slate-200 pb-4">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">TVA / Taxes</span>
                    <span class="text-sm font-black text-slate-900">Incluses</span>
                </div>
                <div class="flex justify-between items-end">
                    <span class="text-lg font-black text-slate-900 uppercase tracking-tighter">Total Net</span>
                    <span class="text-3xl font-black text-slate-900 tracking-tighter">{{ number_format($order->total, 2) }} DH</span>
                </div>
            </div>
        </div>

        <div class="mt-16 pt-8 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                The Vault - Service Conciergerie<br>
                Contact: support@thevault.com
            </p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center md:text-right">
                Document généré électroniquement<br>
                Toute reproduction est interdite
            </p>
        </div>
    </div>
</body>
</html>