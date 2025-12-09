<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pengecekan keamanan tambahan (Double check Auth)
        if (!Auth::check()) {
            return redirect('login');
        }

        if (Auth::user()->role == 'admin') {
            return $next($request);
        }
        
        // UBAH DARI: return redirect()->back();
        // KE: Abort 403 (Respons yang lebih sederhana dan lebih aman di Serverless)
        abort(403, 'Unauthorized access to Admin section.'); 
    }
}
