<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in AND is an admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Let them pass
        }

        // If not, redirect them to home with an error
        return redirect('/')->with('error', 'You do not have admin access.');
    }
}