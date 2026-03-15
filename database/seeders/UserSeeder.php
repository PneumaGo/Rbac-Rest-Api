<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ROOT
        User::updateOrCreate(
            ['email' => 'root@example.com'], // Поиск по email
            [
                'name' => 'System Root',
                'username' => 'rootsystem',
                'phone' => '000000000',
                'password' => Hash::make('password'), // Всегда хешируй пароль!
                'role' => 'root',
            ]
        );

        // ADMIN
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Main Admin',
                'username' => 'admin',
                'phone' => '111111111',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // OPERATOR
        User::updateOrCreate(
            ['email' => 'operator@example.com'],
            [
                'name' => 'Simple Operator',
                'username' => 'operator',
                'phone' => '222222222',
                'password' => Hash::make('password'),
                'role' => 'operator',
            ]
        );
    }
}
