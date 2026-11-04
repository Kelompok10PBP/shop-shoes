<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized admin access.'], 403);
            }
            abort(403, 'Unauthorized access to Stride Admin Console.');
        }

        return $next($request);
    }
}
