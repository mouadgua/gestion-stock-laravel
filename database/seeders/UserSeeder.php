<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer l'admin principal
        User::firstOrCreate(
            ['email' => 'admin@gestionstock.com'],
            [
                'name' => 'Administrateur',
                'email' => 'admin@gestionstock.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'telephone' => '06 00 00 00 00',
                'adresse' => 'Siège social, Paris',
                'email_verified_at' => now(),
            ]
        );

        // Créer quelques utilisateurs de test fixes
        $users = [
            [
                'name' => 'Jean Dupont',
                'email' => 'jean.dupont@email.com',
                'password' => Hash::make('password123'),
                'role' => 'acheteur',
                'telephone' => '06 12 34 56 78',
                'adresse' => '12 rue de la Paix, Paris',
            ],
            [
                'name' => 'Marie Martin',
                'email' => 'marie.martin@email.com',
                'password' => Hash::make('password123'),
                'role' => 'acheteur',
                'telephone' => '06 98 76 54 32',
                'adresse' => '45 avenue des Champs, Lyon',
            ],
            [
                'name' => 'Pierre Bernard',
                'email' => 'pierre.bernard@email.com',
                'password' => Hash::make('password123'),
                'role' => 'acheteur',
                'telephone' => '06 11 22 33 44',
                'adresse' => '78 boulevard Victor, Marseille',
            ],
            [
                'name' => 'Sophie Petit',
                'email' => 'sophie.petit@email.com',
                'password' => Hash::make('password123'),
                'role' => 'acheteur',
                'telephone' => '06 55 66 77 88',
                'adresse' => '23 rue du Commerce, Bordeaux',
            ],
            [
                'name' => 'Thomas Robert',
                'email' => 'thomas.robert@email.com',
                'password' => Hash::make('password123'),
                'role' => 'acheteur',
                'telephone' => '06 99 88 77 66',
                'adresse' => '56 avenue de la République, Lille',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                array_merge($user, ['email_verified_at' => now()])
            );
        }

        // Créer des utilisateurs aléatoires avec la factory
        User::factory()->count(50)->create();
    }
}