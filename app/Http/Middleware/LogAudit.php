<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;


class LogAudit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $auditLog = new AuditLog();
        $auditLog->user_id = Auth::id();
        $auditLog->role = Auth::user()->role;
        $auditLog->action = $request->method();
        $auditLog->path = $request->path();
        $auditLog->save();

        return $next($request);
    }
}
