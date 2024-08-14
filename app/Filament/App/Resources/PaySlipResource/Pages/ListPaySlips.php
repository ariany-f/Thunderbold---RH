<?php

namespace App\Filament\App\Resources\PaySlipResource\Pages;

use App\Filament\App\Resources\PaySlipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;
use App\Models\Employee;
use App\Models\PaySlip;
use Barryvdh\DomPDF\Facade\Pdf;

class ListPaySlips extends ListRecords
{
    protected static string $resource = PaySlipResource::class;

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
        
        $query =  PaySlip::query()
        ->whereIn('employee_id', function ($query) use ($tenantId) {
            $query->select('id')
                  ->from('employees')
                  ->where('team_id', $tenantId);
        });
    
        return $query;
    }
}