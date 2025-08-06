<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@topsol.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('123456'),
                'role' => 'admin',
                'is_active' => true,
                'phone' => '(11) 99999-9999',
            ]
        );

        // Create regular user for testing
        User::firstOrCreate(
            ['email' => 'usuario@topsol.com'],
            [
                'name' => 'Usuário Teste',
                'password' => Hash::make('123456'),
                'role' => 'user',
                'is_active' => true,
                'phone' => '(11) 88888-8888',
            ]
        );

        $this->command->info('Usuários criados com sucesso!');
        $this->command->info('Admin: admin@topsol.com / 123456');
        $this->command->info('Usuário: usuario@topsol.com / 123456');
    }
}
