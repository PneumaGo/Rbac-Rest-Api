<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateRootUser extends Command
{
    protected $signature = 'make:root';
    protected $description = 'Створює першого Root-користувача';

    public function handle()
    {
        $name = $this->ask("Ім'я користувача?");
        $email = $this->ask('Email?');
        $password = $this->secret('Пароль?');

        User::create([
            'name' => $name,
            'username' => 'root',
            'email' => $email,
            'phone' => '0000000000',
            'password' => Hash::make($password),
            'role' => 'root',
        ]);

        $this->info('Root користувач успішно створений!');
    }
}
