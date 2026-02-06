<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rating;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookController extends Controller
{
    use AuthorizesRequests;

    /*
    * Показываем view формы создания книги
    */
    public function create()
    {
        return Inertia::render('Books/Create', [
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
    * Показываем view формы редактирования книги
    */
    public function edit(Book $book)
    {
        $this->authorize('update', $book);

        return Inertia::render('Books/Edit', [
            'book' => [
                'id' => $book->id,
                'title' => $book->title,
                'description' => $book->description,
                'genres' => $book->genres ?? [],
                'isAdult' => $book->is_adult,
                'img' => $book->cover_url,
                'user_id' => $book->user_id,
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
    * Метод обрабатывающий редактирование книги Route put /books/{book}
    */
    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'genres' => 'required|array',
            'genres.*' => 'string',
            'isAdult' => 'boolean',
            'cover' => 'nullable|image|max:2048',
            'removeCover' => 'boolean',
        ]);

        $book->title = $validated['title'];
        $book->description = $validated['description'] ?? null;
        $book->genres = $validated['genres'];
        $book->is_adult = $validated['isAdult'] ?? false;

        // Удаление обложки
        if ($request->boolean('removeCover') && $book->cover_path) {
            Storage::disk('public')->delete($book->cover_path);
            $book->cover_path = null;
        }

        // Замена обложки
        if ($request->hasFile('cover')) {
            if ($book->cover_path) {
                Storage::disk('public')->delete($book->cover_path);
            }

            $path = $request->file('cover')->store('books', 'public');
            $book->cover_path = $path;
        }

        $book->save();

        return redirect()->route('home')->with([
            'success' => 'Книга успешно обновлена!',
            'book' => $book->fresh()
        ]);
    }

    /*
    * Метод удаления книги с обложкой
    */
    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        if ($book->cover_path) {
            Storage::disk('public')->delete($book->cover_path);
        }

        $book->delete();

        return redirect()->route('home')->with('success', 'Книга удалена!');
    }

    /*
    * Метод обрабатывающий создание книги Route post /books
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'genres' => 'required|array',
            'genres.*' => 'string',
            'isAdult' => 'boolean',
            'cover' => 'nullable|image|max:2048',
        ]);

        $book = new Book();
        $book->user_id = Auth::id();
        $book->title = $validated['title'];
        $book->description = $validated['description'] ?? null;
        $book->genres = $validated['genres'];
        $book->is_adult = $validated['isAdult'] ?? false;

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('books', 'public');
            $book->cover_path = $path;
        }

        $book->save();

        return redirect()->route('home')->with('success', 'Книга добавлена!');
    }

    /*
    * Метод обрабатывающий добавления, изменения рейтинга на книгах
    */
    public function rate(Request $request, Book $book)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $this->authorize('rate', $book);

        Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'book_id' => $book->id,
            ],
            ['rating' => $request->rating]
        );

        $book->avg_rating = $book->ratings()->avg('rating') ?? 0;
        $book->ratings_count = $book->ratings()->count();
        $book->save();

        return back()->with('success', 'Оценка сохранена!');
    }
}
