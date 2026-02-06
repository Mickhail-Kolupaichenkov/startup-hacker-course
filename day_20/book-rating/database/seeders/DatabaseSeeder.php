<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Сначала создаем пользователей и админов
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
        ]);

        // Затем создаем книги
        $this->call(BookSeeder::class);
    }
}
