<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
   
    public function run(): void
    {
        // Créer l'admin principal
        User::create([
            'name' => 'Admin Principal',
            'email' => 'admin@myshopify.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Créer un admin secondaire
        User::create([
            'name' => 'Admin Secondaire',
            'email' => 'admin2@myshopify.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Créer un super admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@myshopify.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('3 utilisateurs admin ont été créés avec succès !');
        $this->command->info('Email: admin@myshopify.com | Mot de passe: password');
        $this->command->info('Email: admin2@myshopify.com | Mot de passe: password');
        $this->command->info('Email: superadmin@myshopify.com | Mot de passe: password');
    }
}

