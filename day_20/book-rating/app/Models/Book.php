<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'genres',
        'cover_path',
        'is_adult',
        'avg_rating',
        'ratings_count'
    ];

    protected $casts = [
        'genres' => 'array',
        'is_adult' => 'boolean',
        'avg_rating' => 'decimal:2',
    ];

    protected $appends = ['cover_url'];

    //Книга имеет 1 юзера
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //Книга имеет много рейтингов
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    //Метод для получения средней оценки
    public function getAvgRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    //Метод для получения количества оценок
    public function getRatingsCountAttribute()
    {
        return $this->ratings()->count();
    }

    //Урл обложки
    public function getCoverUrlAttribute(): ?string
    {
        if (!$this->cover_path) return null;
        return Storage::url($this->cover_path);
    }
}
