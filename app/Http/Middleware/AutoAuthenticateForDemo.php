<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AutoAuthenticateForDemo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::firstOrCreate(
            ['email' => 'demo@cushon.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
            ]
        );

        auth()->login($user);

        return $next($request);
    }
}
