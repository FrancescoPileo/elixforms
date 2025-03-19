<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserGroup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $group
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $group)
    {
        $user = Auth::user();

        // Verifica se l'utente appartiene al gruppo specificato
        if (!$user || !$user->groups()->where('group_des', $group)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}