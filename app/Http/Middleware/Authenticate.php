<?php

namespace App\Http\Middleware;

use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Closure;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');

        if ($token === config('app.token')) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
