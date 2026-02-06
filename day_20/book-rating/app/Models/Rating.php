<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    protected $fillable = ['user_id', 'book_id', 'rating'];

    protected $casts = [
        'rating' => 'integer'
    ];

    //Рейтинг относится к 1 юзеру
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //Рейтинг принадлежит 1 книге
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    protected static function booted(): void
    {
        static::saved(function (Rating $rating) {
            // Обновляем средний рейтинг книги
            $book = $rating->book;
            $book->avg_rating = $book->ratings()->avg('rating') ?? 0;
            $book->ratings_count = $book->ratings()->count();
            $book->save();
        });

        static::deleted(function (Rating $rating) {
            // Обновляем средний рейтинг книги после удаления оценки
            $book = $rating->book;
            $book->avg_rating = $book->ratings()->avg('rating') ?? 0;
            $book->ratings_count = $book->ratings()->count();
            $book->save();
        });
    }
}
