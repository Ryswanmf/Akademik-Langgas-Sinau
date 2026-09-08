<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== 'admin') {
            if (auth()->user()->role === 'siswa') {
                return redirect()->route('siswa.dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman Admin.');
            }
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
