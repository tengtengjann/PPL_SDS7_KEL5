<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSreAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated.'
                ], 401);
            }
            return redirect()->route('login')->with('info', 'Silakan masuk terlebih dahulu dengan akun Organisasi SRE.');
        }

        if (!Auth::user()->isSreAdmin()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Akses ditolak. Fitur ini hanya untuk Organisasi SRE.'
                ], 403);
            }
            abort(403, 'Akses ditolak. Fitur ini hanya untuk Organisasi SRE.');
        }

        return $next($request);
    }
}
