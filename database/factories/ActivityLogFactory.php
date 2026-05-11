<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition(): array
    {
        $actions = ['connexion', 'création_commande', 'modification_profil', 'ajout_produit', 'suppression_produit', 'modification_commande', 'inscription', 'ajout_avis'];
        $action = $this->faker->randomElement($actions);
        
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? null,
            'action' => $action,
            'description' => $this->getDescription($action),
            'ip_address' => $this->faker->ipv4(),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }

    private function getDescription(string $action): string
    {
        return match($action) {
            'connexion' => 'Connexion au compte',
            'création_commande' => 'Commande #'.$this->faker->numberBetween(100, 999).' créée',
            'modification_profil' => 'Profil utilisateur modifié',
            'ajout_produit' => 'Produit "'.$this->faker->word().'" ajouté',
            'suppression_produit' => 'Produit supprimé',
            'modification_commande' => 'Commande modifiée - statut mis à jour',
            'inscription' => 'Nouveau compte créé',
            'ajout_avis' => 'Avis déposé sur un produit',
            default => 'Action système',
        };
    }
}