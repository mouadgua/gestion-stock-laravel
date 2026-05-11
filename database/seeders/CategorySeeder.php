<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nom_categorie' => 'Électronique',
                'description' => 'Appareils électroniques, smartphones, ordinateurs, tablettes et accessoires.',
                'slug' => 'electronique',
            ],
            [
                'nom_categorie' => 'Vêtements',
                'description' => 'Mode homme, femme et enfant, vêtements et accessoires de mode.',
                'slug' => 'vêtements',
            ],
            [
                'nom_categorie' => 'Maison & Jardin',
                'description' => 'Décoration, meubles, jardinage et articles pour la maison.',
                'slug' => 'maison-jardin',
            ],
            [
                'nom_categorie' => 'Sports & Loisirs',
                'description' => 'Équipements sportifs, articles de plein air et loisirs.',
                'slug' => 'sports-loisirs',
            ],
            [
                'nom_categorie' => 'Livres & Médias',
                'description' => 'Livres, DVD, CD, jeux vidéo et autres supports médias.',
                'slug' => 'livres-medias',
            ],
            [
                'nom_categorie' => 'Beauté & Santé',
                'description' => 'Produits de beauté, soins, compléments alimentaires et bien-être.',
                'slug' => 'beaute-sante',
            ],
            [
                'nom_categorie' => 'Alimentation',
                'description' => 'Produits alimentaires, épicerie fine et boissons.',
                'slug' => 'alimentation',
            ],
            [
                'nom_categorie' => 'Jouets & Enfants',
                'description' => 'Jouets, jeux, puériculture et articles pour bébés.',
                'slug' => 'jouets-enfants',
            ],
            [
                'nom_categorie' => 'Auto & Moto',
                'description' => 'Pièces détachées, accessoires et équipements pour véhicules.',
                'slug' => 'auto-moto',
            ],
            [
                'nom_categorie' => 'Animaux',
                'description' => 'Alimentation, accessoires et soins pour animaux de compagnie.',
                'slug' => 'animaux',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}