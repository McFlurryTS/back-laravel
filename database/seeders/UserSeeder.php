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
                'username' => 'mechax3',
                'email' => 'mechax3@email.com',
                'password' => Hash::make('12345678'),
            ],
            [
                'username' => 'admin',
                'email' => 'admin@email.com',
                'password' => Hash::make('admin123'),
            ],
            [
                'username' => 'usuario_demo',
                'email' => 'demo@email.com',
                'password' => Hash::make('demo456'),
            ],
        ];

        foreach ($usuarios as $usuario) {
            User::create($usuario);
        }
    }
}
