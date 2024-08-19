<?php

namespace App\Filament\Resources\DependentResource\Pages;

use App\Filament\Resources\DependentResource;
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
