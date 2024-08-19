<?php

namespace App\Filament\App\Resources\DependentResource\Pages;

use App\Filament\App\Resources\DependentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;
use App\Models\Employee;
use App\Models\Dependent;

class ListDependents extends ListRecords
{
    protected static string $resource = DependentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    
    /**
     * Retorna a consulta para a tabela.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getTableQuery(): Builder
    {
        $tenantId = Filament::getTenant()->id; // Substitua isso conforme a sua implementação de multi-tenancy
        
        $query =  Dependent::query()
        ->whereIn('employee_id', function ($query) use ($tenantId) {
            $query->select('id')
                  ->from('employees')
                  ->where('team_id', $tenantId);
        });
    
        return $query;
    }
}
