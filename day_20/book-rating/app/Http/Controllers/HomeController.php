<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Rating;

class HomeController extends Controller
{
    /*
    * Показываем view всех книг, стартовая страница
    */
    public function index()
    {
        //Получаем все книги
        $books = Book::with(['user:id,name'])
            ->latest()
            ->get()
            ->map(function ($book) {
                // Получаем оценки текущего пользователя если он авторизован
                $userRating = null;
                if (Auth::check()) {
                    $rating = $book->ratings()
                        ->where('user_id', Auth::id())
                        ->first();
                    $userRating = $rating ? $rating->rating : null;
                }

                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'description' => $book->description,
                    'genres' => $book->genres ?? [],
                    'img' => $book->cover_url,
                    'isAdult' => $book->is_adult,
                    'stars' => (float) $book->avg_rating,
                    'user_rating' => $userRating, // Оценка текущего пользователя
                    'ratings_count' => $book->ratings_count,
                    'user' => $book->user,
                    'user_id' => $book->user_id,
                ];
            });

        return Inertia::render('Home', [
            'books' => $books,
            'canCreate' => Auth::check(),
            'auth' => [
                'user' => Auth::user() ? [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'role' => Auth::user()->role,
                ] : null
            ]
        ]);
    }

    /*
    * Показываем view страницы о нас
    */
    public function about()
    {
        return Inertia::render('About');
    }
}
