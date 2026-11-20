<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SiswaAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('hasil_tes_id')) {
            return redirect()->route('siswa.login');
        }
        return $next($request);
    }
}
