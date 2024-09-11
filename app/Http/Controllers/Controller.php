<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function index() {
        if (auth()->check()) {
            // Redireciona com base no perfil do usuário
            if (auth()->user()->is_admin) {
                return redirect('/admin');
            } elseif (auth()->user()->employee_id) {
                return redirect('/employee');
            } else {
                return redirect('/app');
            }
        }
        else{
            return redirect('/app/login');
        }
    }
}
