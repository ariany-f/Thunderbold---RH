<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureIsEmployee
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
        // Verifique se o usuário é um employee e permita o acesso ou redirecione conforme necessário
        if (!auth()->check() || !auth()->user()->employee_id) {
            return redirect('/app/login'); // Ajuste para o caminho apropriado
        }

        return $next($request);
    }
}
