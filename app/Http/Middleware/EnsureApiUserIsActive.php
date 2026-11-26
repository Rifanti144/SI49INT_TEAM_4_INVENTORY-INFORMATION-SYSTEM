<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->is_active, 403, 'Your account has been deactivated.');

        return $next($request);
    }
}
