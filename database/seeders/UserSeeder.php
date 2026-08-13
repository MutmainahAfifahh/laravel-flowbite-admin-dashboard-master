<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'email' => 'admin@stockify.com',
                'name'  => 'Admin Stockify',
                'role'  => 'Admin',
                'password' => Hash::make('password'),
            ],
            [
                'email' => 'manajer@stockify.com',
                'name'  => 'Manajer Gudang',
                'role'  => 'Manajer Gudang',
                'password' => Hash::make('password'),
            ],
            [
                'email' => 'staff@stockify.com',
                'name'  => 'Staff Gudang',
                'role'  => 'Staff Gudang',
                'password' => Hash::make('password'),
            ],
            [
                'email' => 'admin@gmail.com',
                'name'  => 'Admin Stockify (Gmail)',
                'role'  => 'Admin',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name'              => $userData['name'],
                    'role'              => $userData['role'],
                    'password'          => $userData['password'],
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}