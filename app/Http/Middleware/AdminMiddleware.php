<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            auth()->check() &&
            in_array(auth()->user()->role, ['admin_master', 'admin_cabang'])
        ) {
            return $next($request);
        }

        abort(403, 'Akses ditolak');
    }
}