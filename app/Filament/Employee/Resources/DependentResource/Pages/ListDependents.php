<?php

namespace App\Filament\Employee\Resources\DependentResource\Pages;

use App\Filament\Employee\Resources\DependentResource;
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
            //
        ];
    }

    /**
     * Retorna a consulta para a tabela.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getTableQuery(): Builder
    {
        $userId = auth()->user()->employee_id;

        $query =  Dependent::query()
        ->whereIn('employee_id', function ($query) use ($userId) {
            $query->select('id')
                  ->from('employees')
                  ->where('id', $userId);
        });
    
        return $query;
    }
}
