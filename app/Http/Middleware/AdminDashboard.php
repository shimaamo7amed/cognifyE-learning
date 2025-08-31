<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminDashboard
{
    
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('filament.admin.auth.login');
        }

        $user = auth()->user();
        if ($user->user_type !== 'admin' && !$user->hasRole('admin')) {

            auth()->logout();
            return redirect()->route('filament.admin.auth.login')
                ->withErrors(['email' => 'no access']);
        }

        return $next($request);
    }
}