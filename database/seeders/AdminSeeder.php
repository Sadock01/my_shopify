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
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Créer un admin secondaire
        User::create([
            'name' => 'Admin Secondaire',
            'email' => 'admin2@myshopify.com',
            'password' => Hash::make('admin456'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Créer un super admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@myshopify.com',
            'password' => Hash::make('superadmin789'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('3 utilisateurs admin ont été créés avec succès !');
        $this->command->info('Email: admin@myshopify.com | Mot de passe: admin123');
        $this->command->info('Email: admin2@myshopify.com | Mot de passe: admin456');
        $this->command->info('Email: superadmin@myshopify.com | Mot de passe: superadmin789');
    }
}

