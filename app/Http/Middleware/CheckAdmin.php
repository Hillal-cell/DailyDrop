<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('CheckAdmin middleware loaded.');

        if (Auth::check() && Auth::user()->role  === 'admin') {
            return $next($request);
        }


        Log::warning('CheckAdmin middleware: User is not admin.');
        return redirect('/dashboard')->with('error', 'You do not have access to the page you requested.');
    }
}
