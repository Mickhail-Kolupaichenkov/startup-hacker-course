<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $books = [
            [
                'id' => 1,
                'title' => 'Мастер и Маргарита',
                'description' => '«Мастер и Маргарита» — роман Михаила Афанасьевича Булгакова, работа над которым началась, по одним данным, в 1928 году, по другим — в 1929-м, и продолжалась вплоть до смерти писателя в марте 1940 года.',
                'genres' => ['Роман'],
                'img' => '/images/books/book1.png',
                'isAdult' => false,
                'stars' => 5
            ],
            [
                'id' => 2,
                'title' => 'Бэтман Аполло',
                'description' => 'Главный герой — Рама, сравнительно недавно ставший вампиром. Ему предстоит разобраться в вампирском мироздании и подняться по иерархической лестнице, став Кавалером Ночи и ныряльщиком.',
                'genres' => ['Роман'],
                'img' => '/images/books/book2.png',
                'isAdult' => true,
                'stars' => 4
            ],
            [
                'id' => 3,
                'title' => 'Стратегическое управление на основе маркетингового анализа. Инструменты, проблемы, ситуации',
                'description' => 'Книга в которой автор описывает классические и собственные инструменты стратегического управления и маркетингового анализа.',
                'genres' => ['Бизнес-книга'],
                'img' => '/images/books/book3.png',
                'isAdult' => false,
                'stars' => 0
            ],
            [
                'id' => 4,
                'title' => 'Чистый код: создание, анализ и рефакторинг. Библиотека программиста',
                'description' => 'Даже плохой программный код может работать. Однако если код не является «чистым», это всегда будет мешать развитию проекта и компании-разработчика, отнимая значительные ресурсы на его поддержку и «укрощение».',
                'genres' => ['Техническая-литература'],
                'img' => '/images/books/book4.png',
                'isAdult' => true,
                'stars' => 3
            ],
            [
                'id' => 5,
                'title' => 'Копирайтинг: как не съесть собаку. Создаем тексты, которые продают',
                'description' => 'Эта книга - набор методик, приемов и секретов по написанию продающих текстов (текстов для сайтов, полиграфии, коммерческих предложений и других рекламных целей).',
                'genres' => ['Бизнес-книга'],
                'img' => '/images/books/book5.png',
                'isAdult' => false,
                'stars' => 0
            ],
            [
                'id' => 6,
                'title' => 'Магия утра. Как первый час дня определяет ваш успех',
                'description' => 'Книга, которая помогла тысячам людей изменить жизнь за счет правильного начала дня и утренних ритуалов.',
                'genres' => ['Бизнес-книга'],
                'img' => '/images/books/book6.png',
                'isAdult' => false,
                'stars' => 0
            ]
        ];

        return Inertia::render('Home', [
            'books' => $books
        ]);
    }

    public function about()
    {
        $integerValue = 55;

        $stringValue = "Это строка";

        $arrayValue = ["PHP", "JavaScript", "Vue.js", "Laravel", "Inertia"];

        $assocArray = [
            'author' => 'Михаил Булгаков',
            'year' => 1940,
            'genre' => 'Роман',
            'pages' => 384,
            'language' => 'Русский'
        ];
        return Inertia::render('About', [
            'integerExample' => $integerValue,
            'stringExample' => $stringValue,
            'arrayExample' => $arrayValue,
            'assocArrayExample' => $assocArray,
        ]);
    }

    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }

    public function profile()
    {
        return Inertia::render('Profile');
    }

    public function scrollpage()
    {
        return Inertia::render('ScrollPage');
    }

    public function submitForm(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2',
            'email' => 'required|email'
        ]);

        return back();
    }
}
