<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->error('Aucune catégorie trouvée. Exécutez d\'abord CategorySeeder.');
            return;
        }

        // Produits prédéfinis par catégorie
        $products = [
            // Électronique
            [
                'nom_produit' => 'Smartphone Galaxy X Pro',
                'description' => 'Smartphone dernière génération avec écran AMOLED 6.7 pouces, 256Go de stockage, appareil photo 108MP et batterie 5000mAh.',
                'prix' => 699.99,
                'stock' => 45,
                'slug' => 'smartphone-galaxy-x-pro',
                'categorie' => 'Électronique',
            ],
            [
                'nom_produit' => 'Ordinateur Portable UltraBook 15',
                'description' => 'Ordinateur portable ultraléger avec processeur Intel i7, 16Go RAM, SSD 512Go et écran 15.6 pouces Full HD.',
                'prix' => 899.99,
                'stock' => 23,
                'slug' => 'ordinateur-portable-ultrabook-15',
                'categorie' => 'Électronique',
            ],
            [
                'nom_produit' => 'Écouteurs Sans Fil Pro',
                'description' => 'Écouteurs Bluetooth avec réduction de bruit active, autonomie 30h, résistance à l\'eau IPX5.',
                'prix' => 149.99,
                'stock' => 120,
                'slug' => 'ecouteurs-sans-fil-pro',
                'categorie' => 'Électronique',
            ],
            [
                'nom_produit' => 'Tablette Graphique Design',
                'description' => 'Tablette graphique professionnelle 15.6 pouces avec stylet sans batterie et 8192 niveaux de pression.',
                'prix' => 349.99,
                'stock' => 35,
                'slug' => 'tablette-graphique-design',
                'categorie' => 'Électronique',
            ],

            // Vêtements
            [
                'nom_produit' => 'Veste en Cuir Homme',
                'description' => 'Veste en cuir véritable, coupe slim, doublure polyester, disponible en noir et marron.',
                'prix' => 189.99,
                'stock' => 28,
                'slug' => 'veste-en-cuir-homme',
                'categorie' => 'Vêtements',
            ],
            [
                'nom_produit' => 'Robe Élégante Soirée',
                'description' => 'Robe longue élégante en dentelle, parfaite pour les occasions spéciales.',
                'prix' => 79.99,
                'stock' => 42,
                'slug' => 'robe-elegante-soiree',
                'categorie' => 'Vêtements',
            ],
            [
                'nom_produit' => 'Baskets Sport Confort',
                'description' => 'Baskets légères avec semelle amortissante, respirantes, idéales pour le sport et le quotidien.',
                'prix' => 69.99,
                'stock' => 85,
                'slug' => 'baskets-sport-confort',
                'categorie' => 'Vêtements',
            ],

            // Maison & Jardin
            [
                'nom_produit' => 'Canapé 3 Places Scandinave',
                'description' => 'Canapé moderne style scandinave, tissu gris, pieds en bois, assise confortable.',
                'prix' => 449.99,
                'stock' => 12,
                'slug' => 'canape-3-places-scandinave',
                'categorie' => 'Maison & Jardin',
            ],
            [
                'nom_produit' => 'Lampe de Bureau LED',
                'description' => 'Lampe LED avec 5 niveaux de luminosité, bras articulé, port USB pour recharge.',
                'prix' => 39.99,
                'stock' => 67,
                'slug' => 'lampe-de-bureau-led',
                'categorie' => 'Maison & Jardin',
            ],
            [
                'nom_produit' => 'Set de 3 Plantes Décoratives',
                'description' => 'Ensemble de 3 plantes artificielles dans des pots en céramique, décoration intérieure.',
                'prix' => 34.99,
                'stock' => 55,
                'slug' => 'set-de-3-plantes-decoratives',
                'categorie' => 'Maison & Jardin',
            ],

            // Sports & Loisirs
            [
                'nom_produit' => 'Tapis de Yoga Premium',
                'description' => 'Tapis de yoga antidérapant 6mm d\'épaisseur, écologique, avec sac de transport.',
                'prix' => 29.99,
                'stock' => 95,
                'slug' => 'tapis-de-yoga-premium',
                'categorie' => 'Sports & Loisirs',
            ],
            [
                'nom_produit' => 'Haltères Réglables 20kg',
                'description' => 'Paire d\'haltères réglables de 2.5kg à 20kg, idéal pour la musculation à domicile.',
                'prix' => 149.99,
                'stock' => 30,
                'slug' => 'halteres-reglables-20kg',
                'categorie' => 'Sports & Loisirs',
            ],
            [
                'nom_produit' => 'Vélo de Ville Pliable',
                'description' => 'Vélo urbain pliable, 7 vitesses, cadre aluminium léger, roues 20 pouces.',
                'prix' => 329.99,
                'stock' => 18,
                'slug' => 'velo-de-ville-pliable',
                'categorie' => 'Sports & Loisirs',
            ],

            // Beauté & Santé
            [
                'nom_produit' => 'Sérum Vitamine C',
                'description' => 'Sérum visage à la vitamine C pure 20%, anti-âge, éclaircissant, 30ml.',
                'prix' => 24.99,
                'stock' => 150,
                'slug' => 'serum-vitamine-c',
                'categorie' => 'Beauté & Santé',
            ],
            [
                'nom_produit' => 'Brosse Soufflante 5en1',
                'description' => 'Brosse soufflante multifonction avec 5 embouts interchangeables pour tous types de cheveux.',
                'prix' => 49.99,
                'stock' => 78,
                'slug' => 'brosse-soufflante-5en1',
                'categorie' => 'Beauté & Santé',
            ],
        ];

        foreach ($products as $productData) {
            $category = $categories->firstWhere('nom_categorie', $productData['categorie']);
            
            if ($category) {
                Product::firstOrCreate(
                    ['slug' => $productData['slug']],
                    [
                        'nom_produit' => $productData['nom_produit'],
                        'description' => $productData['description'],
                        'prix' => $productData['prix'],
                        'stock' => $productData['stock'],
                        'image' => null,
                        'est_actif' => true,
                        'categorie_id' => $category->id,
                    ]
                );
            }
        }

        // Créer des produits aléatoires avec la factory
        Product::factory()->count(50)->create();
    }
}