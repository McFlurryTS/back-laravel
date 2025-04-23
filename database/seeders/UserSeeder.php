<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        //User::truncate(); // Opcional: Limpia la tabla para evitar duplicados

        $usuarios = [
            [
                'username' => 'Mauricio',
                'email' => 'mauricio@email.com',
                'password' => Hash::make('12345'),
            ],
            [
                'username' => 'Antonio',
                'email' => 'antonio@email.com',
                'password' => Hash::make('12345'),
            ],
            [
                'username' => 'Alan',
                'email' => 'alan@email.com',
                'password' => Hash::make('12345'),
            ],
            [
                'username' => 'Gerardo',
                'email' => 'gerardo@email.com',
                'password' => Hash::make('12345'),
            ],
            [
                'username' => 'Pablo',
                'email' => 'pablo@email.com',
                'password' => Hash::make('12345'),
            ],
        ];

        foreach ($usuarios as $usuario) {
            User::create($usuario);
        }
    }
}
