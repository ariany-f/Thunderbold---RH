<?php

namespace App\Filament\Employee\Resources\PaySlipResource\Pages;

use App\Filament\Employee\Resources\PaySlipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;
use App\Models\Employee;
use App\Models\PaySlip;

class ListPaySlips extends ListRecords
{
    protected static string $resource = PaySlipResource::class;

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

        $query =  PaySlip::query()
        ->whereIn('employee_id', function ($query) use ($userId) {
            $query->select('id')
                  ->from('employees')
                  ->where('id', $userId);
        });
    
        return $query;
    }
}