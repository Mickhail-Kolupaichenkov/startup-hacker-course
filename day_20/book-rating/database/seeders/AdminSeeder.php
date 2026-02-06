<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Проверяем, нет ли уже администратора
        if (!User::where('email', 'admin@example.com')->exists()) {
            // Создаем администратора
            User::create([
                'name' => 'Колупайченков Михаил',
                'email' => 'admin@example.com',
                'password' => Hash::make('3434'),
                'role' => 'admin',
            ]);

            $this->command->info('Администратор создан!');
            $this->command->info('Email: admin@example.com');
            $this->command->info('Password: 3434');
        } else {
            $this->command->info('Администратор уже существует.');
        }

        // Создаем тестового пользователя
        if (!User::where('email', 'user@example.com')->exists()) {
            User::create([
                'name' => 'Алексей Фоменков',
                'email' => 'user@example.com',
                'password' => Hash::make('3434'),
                'role' => 'user',
            ]);
        }
    }
}
