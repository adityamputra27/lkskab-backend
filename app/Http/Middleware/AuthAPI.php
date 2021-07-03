<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LoginToken;
use App\Models\User;
use Auth;

class AuthAPI
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
        $token = $request->get('token');

        $find = LoginToken::where('token', $token)->first();

        if (!$find || !$token) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized user!'
            ], 401);
        }

        $user = User::find($find->user_id);

        Auth::login($user);

        return $next($request);
    }
}
