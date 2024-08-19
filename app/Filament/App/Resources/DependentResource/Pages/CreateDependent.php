<?php

namespace App\Filament\App\Resources\DependentResource\Pages;

use App\Filament\App\Resources\DependentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDependent extends CreateRecord
{
    protected static string $resource = DependentResource::class;
    
    public function mount(): void
    {
        // Passa o parâmetro `employee_id` para a URL
        $employeeId = request()->query('employee_id');
        $this->form->fill([
            'employee_id' => $employeeId,
        ]);
    }
}
