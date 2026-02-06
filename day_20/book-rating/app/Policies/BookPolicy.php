<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Book;

class BookPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Все могут видеть список книг
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Book $book): bool
    {
        return true; // Все могут видеть конкретную книгу
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user !== null; // Авторизованные пользователи могут создавать
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Book $book): bool
    {
        // Автор книги или админ могут редактировать
        return $user->id === $book->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Book $book): bool
    {
        // Автор книги или админ могут удалять
        return $user->id === $book->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Book $book): bool
    {
        return $user->isAdmin(); // Только админ
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Book $book): bool
    {
        return $user->isAdmin(); // Только админ
    }

    /**
     * Determine whether the user can rate the book.
     */
    public function rate(User $user, Book $book): bool
    {
        // Разрешаем оценивать любые книги, включая свои
        return $user !== null; // Просто проверяем авторизацию
    }
}
