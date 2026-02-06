<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Проверяем авторизацию
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Проверяем роль
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }

        return $next($request);
    }
}
