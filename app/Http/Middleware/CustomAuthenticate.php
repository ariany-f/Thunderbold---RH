<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            return url('app/login');
        }

        return null;
    }

    
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        if (auth()->check()) {

            $referer = $request->headers->get('referer');
            if (parse_url($referer, PHP_URL_PATH) === '/app/login') {
                // Redireciona com base no perfil do usuário
                if (auth()->user()->is_admin) {
                    return redirect('/admin');
                } elseif (auth()->user()->employee_id) {
                    return redirect('/employee');
                }
            }
        }

        return $next($request);
    }
}
