<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensure the user is an analyst.
 */
class EnsureUserIsAnalyst
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (null === $user) {
            abort(403, 'You need to be logged in to access this page.');
        }

        if ($user->isAnalyst()) {
            return $next($request);
        }

        abort(403, 'You are not authorized to access this page.');
    }
}
