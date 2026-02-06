<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /*
    * Показываем view админ-панели с пользователями
    */
    public function index()
    {
        // Получаем всех пользователей
        $users = User::select(['id', 'name', 'email', 'role', 'created_at'])
            ->latest()
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'created_at' => $user->created_at->format('d.m.Y H:i'),
                    'books_count' => $user->books()->count(),
                ];
            });

        return Inertia::render('Dashboard/Index', [
            'users' => $users,
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
    * Показываем view редактирования информации о пользователе
    */
    public function edit(User $user)
    {
        return Inertia::render('Dashboard/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at->format('d.m.Y H:i'),
            ],
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
    * Метод update данных об пользователе Route put /users/{user}
    */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
        ]);

        // Нельзя менять роль самому себе
        if ($user->id === Auth::id() && $validated['role'] !== 'admin') {
            return back()->withErrors(['role' => 'Вы не можете изменить свою роль']);
        }

        $user->update($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Пользователь обновлен');
    }

    /*
    * Метод удаляющий пользователя с его книгами
    */
    public function destroy(User $user)
    {
        // Нельзя удалить себя
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Вы не можете удалить себя']);
        }

        // Удаляем все книги пользователя и их обложки
        foreach ($user->books as $book) {
            if ($book->cover_path) {
                Storage::disk('public')->delete($book->cover_path);
            }
        }

        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Пользователь удален');
    }
}
