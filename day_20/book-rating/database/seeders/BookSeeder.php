<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // Получаем всех пользователей
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('Сначала создайте пользователей!');
            return;
        }

        // Массив книг с разными жанрами
        $books = [
            [
                'title' => 'Мастер и Маргарита',
                'description' => 'Роман Михаила Булгакова, работа над которым началась в конце 1920-х годов и продолжалась вплоть до смерти писателя.',
                'genres' => ['Роман', 'Мистика', 'Философия'],
                'is_adult' => false,
            ],
            [
                'title' => '1984',
                'description' => 'Роман-антиутопия Джорджа Оруэлла, изданный в 1949 году.',
                'genres' => ['Антиутопия', 'Политика', 'Фантастика'],
                'is_adult' => false,
            ],
            [
                'title' => 'Преступление и наказание',
                'description' => 'Роман Фёдора Михайловича Достоевского, впервые опубликованный в 1866 году.',
                'genres' => ['Классика', 'Психология', 'Философия'],
                'is_adult' => false,
            ],
            [
                'title' => 'Война и мир',
                'description' => 'Роман-эпопея Льва Николаевича Толстого, описывающий русское общество в эпоху войн против Наполеона.',
                'genres' => ['Классика', 'Исторический', 'Роман'],
                'is_adult' => false,
            ],
            [
                'title' => 'Маленький принц',
                'description' => 'Аллегорическая повесть-сказка Антуана де Сент-Экзюпери.',
                'genres' => ['Сказка', 'Философия', 'Детская литература'],
                'is_adult' => false,
            ],
            [
                'title' => 'Три товарища',
                'description' => 'Роман Эриха Марии Ремарка, опубликованный в 1936 году.',
                'genres' => ['Роман', 'Драма', 'Классика'],
                'is_adult' => false,
            ],
        ];

        // Создаем 6 книг и распределяем их между пользователями
        foreach ($books as $index => $bookData) {
            // Распределяем книги между пользователями по кругу
            $user = $users[$index % $users->count()];

            Book::create([
                'user_id' => $user->id,
                'title' => $bookData['title'],
                'description' => $bookData['description'],
                'genres' => $bookData['genres'],
                'is_adult' => $bookData['is_adult'],
                'avg_rating' => rand(30, 50) / 10, // Случайный рейтинг от 3.0 до 5.0
                'ratings_count' => rand(5, 100), // Случайное количество оценок
            ]);
        }

        $this->command->info('Создано 6 книг!');
    }
}
