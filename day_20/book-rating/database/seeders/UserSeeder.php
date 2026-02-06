<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Массив для 5 пользователей: 2 админа + 3 обычных
        $users = [
            // Админы
            [
                'name' => 'Администратор Системы',
                'email' => 'admin1@example.com',
                'password' => 'password123',
                'role' => 'admin',
            ],
            [
                'name' => 'Модератор',
                'email' => 'admin2@example.com',
                'password' => 'password123',
                'role' => 'admin',
            ],
            // Обычные пользователи
            [
                'name' => 'Алексей Иванов',
                'email' => 'user1@example.com',
                'password' => 'password123',
                'role' => 'user',
            ],
            [
                'name' => 'Мария Петрова',
                'email' => 'user2@example.com',
                'password' => 'password123',
                'role' => 'user',
            ],
            [
                'name' => 'Сергей Сидоров',
                'email' => 'user3@example.com',
                'password' => 'password123',
                'role' => 'user',
            ],
        ];

        foreach ($users as $userData) {
            // Проверяем, существует ли пользователь
            if (!User::where('email', $userData['email'])->exists()) {
                User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'role' => $userData['role'],
                ]);
            }
        }

        $this->command->info('Создано 5 пользователей (2 админа + 3 обычных)!');
        $this->command->info('Пароль для всех: password123');
    }
}
