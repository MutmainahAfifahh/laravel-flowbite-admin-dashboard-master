<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@stockify.com'],
            [
                'name' => 'Admin Stockify',
                'role' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'manajer@stockify.com'],
            [
                'name' => 'Manajer Gudang',
                'role' => 'Manajer Gudang',
                'password' => Hash::make('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff@stockify.com'],
            [
                'name' => 'Staff Gudang',
                'role' => 'Staff Gudang',
                'password' => Hash::make('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Stockify (Gmail)',
                'role' => 'Admin',
                'password' => Hash::make('password123'),
            ]
        );
    }
}