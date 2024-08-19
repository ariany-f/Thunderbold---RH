<?php

namespace App\Filament\Employee\Resources\MyTeamResource\Pages;

use App\Filament\Employee\Resources\MyTeamResource;
use App\Models\Employee;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListMyTeams extends ListRecords
{
    protected static string $resource = MyTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // Actions\CreateAction::make(),
        ];
    }

     /**
     * Retorna a consulta para a tabela.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getTableQuery(): Builder
    {
        return Employee::query()->where('manager_id', auth()->user()->employee_id);
    }
}
