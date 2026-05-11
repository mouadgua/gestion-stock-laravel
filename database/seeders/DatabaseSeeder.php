<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            ReviewSeeder::class,
        ]);

        $this->command->info('Tous les seeders ont été exécutés avec succès !');
        $this->command->info('----------------------------------------');
        $this->command->info('Comptes de test disponibles :');
        $this->command->info('  Admin : admin@gestionstock.com / admin123');
        $this->command->info('  Client : jean.dupont@email.com / password123');
        $this->command->info('  (et 4 autres clients de test)');
        $this->command->info('----------------------------------------');
    }
}