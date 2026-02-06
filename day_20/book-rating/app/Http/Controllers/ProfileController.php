<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /*
    * Показываем view профиля с книгами пользователя
    */
    public function index()
    {
        // Получаем книги текущего пользователя
        $books = Book::with(['user:id,name'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->map(function ($book) {
                // Получаем оценки текущего пользователя
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
                    'user_rating' => $userRating,
                    'ratings_count' => $book->ratings_count,
                    'user' => $book->user,
                    'user_id' => $book->user_id,
                ];
            });

        return Inertia::render('Profile', [
            'books' => $books,
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
}
