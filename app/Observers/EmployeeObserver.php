<?php

namespace App\Observers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Str;

class EmployeeObserver
{
    /**
     * Handle the Employee "created" event.
     */
    public function created(Employee $employee): void
    {
        // Supondo que o modelo Employee tenha os campos 'name' e 'email'
        $user = User::create([
            'name' => $employee->first_name . ' ' . $employee->last_name,
            'email' => $employee->email,
            'password' => bcrypt('Mudar123@'), // Gera uma senha aleatória
        ]);

        // Associa o User criado ao Employee
        $employee->user()->save($user);
    }
}
