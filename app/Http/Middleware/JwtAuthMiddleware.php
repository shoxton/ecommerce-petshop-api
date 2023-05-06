<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\JWTService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthMiddleware
{


    private JWTService $jwtService;

    public function __construct(JWTService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the Authorization header is present
        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Verify and decode the JWT token
        try {
            $decodedToken = $this->jwtService->validateToken($bearerToken);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid token', 'trace' => $e], 401);
        }


        $claims = $this->jwtService->getTokenClaims($bearerToken);

        $user = User::where('uuid', $claims['user_uuid'])->first();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 422);
        }

        $request->attributes->set('user', $user);

        return $next($request);
    }
}
