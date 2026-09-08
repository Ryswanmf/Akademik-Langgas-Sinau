<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
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

        if (auth()->user()->role !== 'siswa') {
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Halaman ini khusus untuk Siswa.');
            }
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
