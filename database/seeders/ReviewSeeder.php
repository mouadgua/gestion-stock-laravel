<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $clients = User::where('role', 'acheteur')->get();

        if ($products->isEmpty() || $clients->isEmpty()) {
            $this->command->error('Aucun produit ou client trouvé. Exécutez d\'abord les autres seeders.');
            return;
        }

        // Avis prédéfinis pour les produits spécifiques
        $reviewsData = [
            'smartphone-galaxy-x-pro' => [
                ['rating' => 5, 'comment' => 'Excellent smartphone, très performant et belle autonomie !'],
                ['rating' => 4, 'comment' => 'Très bon téléphone, juste un peu cher à mon goût.'],
                ['rating' => 5, 'comment' => 'La qualité photo est incroyable, je recommande !'],
                ['rating' => 4, 'comment' => 'Bon rapport qualité-prix, écran magnifique.'],
            ],
            'ordinateur-portable-ultrabook-15' => [
                ['rating' => 5, 'comment' => 'Parfait pour le télétravail, léger et puissant.'],
                ['rating' => 4, 'comment' => 'Très satisfait, autonomie correcte et bonnes performances.'],
                ['rating' => 3, 'comment' => 'Bon ordinateur mais le ventilateur est un peu bruyant.'],
            ],
            'ecouteurs-sans-fil-pro' => [
                ['rating' => 5, 'comment' => 'Meilleurs écouteurs que j\'ai eus, réduction de bruit top !'],
                ['rating' => 5, 'comment' => 'Son excellent et confortables même après plusieurs heures.'],
                ['rating' => 4, 'comment' => 'Très bons écouteurs, juste le boîtier un peu fragile.'],
                ['rating' => 5, 'comment' => 'Parfaits pour les transports en commun.'],
            ],
            'veste-en-cuir-homme' => [
                ['rating' => 5, 'comment' => 'Cuir de très bonne qualité, coupe parfaite.'],
                ['rating' => 4, 'comment' => 'Belle veste, taille bien, livraison rapide.'],
            ],
            'canape-3-places-scandinave' => [
                ['rating' => 5, 'comment' => 'Magnifique canapé, très confortable et design.'],
                ['rating' => 4, 'comment' => 'Bon canapé, montage facile, bon rapport qualité-prix.'],
                ['rating' => 3, 'comment' => 'Correct mais l\'assise est un peu ferme.'],
            ],
            'tapis-de-yoga-premium' => [
                ['rating' => 5, 'comment' => 'Excellent tapis, antidérapant et épais, parfait !'],
                ['rating' => 5, 'comment' => 'Je recommande, très bonne qualité et prix raisonnable.'],
                ['rating' => 4, 'comment' => 'Bon tapis de yoga, odeur au début mais passe vite.'],
            ],
        ];

        // Créer les avis prédéfinis
        foreach ($reviewsData as $slug => $reviews) {
            $product = $products->firstWhere('slug', $slug);
            
            if ($product) {
                foreach ($reviews as $reviewData) {
                    $client = $clients->random();
                    
                    Review::firstOrCreate(
                        [
                            'product_id' => $product->id_produit,
                            'user_id' => $client->id,
                        ],
                        [
                            'rating' => $reviewData['rating'],
                            'comment' => $reviewData['comment'],
                            'is_approved' => true,
                        ]
                    );
                }
            }
        }

        // Créer des avis aléatoires pour tous les produits
        $existingReviews = Review::selectRaw('product_id, user_id')->get();
        $existingPairs = $existingReviews->map(fn($r) => $r->product_id . '-' . $r->user_id)->toArray();

        $randomReviewsCount = 100;
        $created = 0;
        $attempts = 0;
        $maxAttempts = 500;

        while ($created < $randomReviewsCount && $attempts < $maxAttempts) {
            $product = $products->random();
            $client = $clients->random();
            
            $pair = $product->id_produit . '-' . $client->id;
            
            if (!in_array($pair, $existingPairs)) {
                $existingPairs[] = $pair;
                
                // Déterminer le type d'avis (plus d'avis positifs que négatifs)
                $rand = mt_rand(1, 100);
                if ($rand <= 60) {
                    Review::factory()->positive()->create([
                        'product_id' => $product->id_produit,
                        'user_id' => $client->id,
                    ]);
                } elseif ($rand <= 85) {
                    Review::factory()->average()->create([
                        'product_id' => $product->id_produit,
                        'user_id' => $client->id,
                    ]);
                } else {
                    Review::factory()->negative()->create([
                        'product_id' => $product->id_produit,
                        'user_id' => $client->id,
                    ]);
                }
                
                $created++;
            }
            
            $attempts++;
        }

        $this->command->info("$created avis aléatoires créés avec succès.");
    }
}