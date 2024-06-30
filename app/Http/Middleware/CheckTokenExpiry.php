<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckTokenExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->user()->currentAccessToken();

        // Atur masa berlaku token dalam menit
        $tokenExpiryMinutes = config('app.token_expiry_minutes', 60); // 1 jam

        if ($token->created_at->diffInMinutes(Carbon::now()) > $tokenExpiryMinutes) {
            $token->delete(); // Hapus token yang kadaluarsa
            return response()->json(['message' => 'Token has expired'], 401);
        }

        return $next($request);
    }
}
