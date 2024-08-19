<?php

namespace App\Filament\App\Resources\EmployeeResource\Pages;

use App\Filament\App\Resources\EmployeeResource;
use App\Filament\App\Resources\DependentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;
use App\Models\Employee;
use Illuminate\Support\Facades\Request;
use App\Models\Dependent;

class ViewDependents extends ListRecords
{
    protected static string $resource = DependentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->url(fn () => route('filament.app.resources.dependents.create', [
                    'employee_id' => request()->route()->parameters()['record'],
                    'tenant' => Filament::getTenant(),
                ])),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.app.resources.employees.index', ['tenant' => Filament::getTenant()]) => ucwords(trans_choice('custom.employee.label', 2)),
            ucwords(trans_choice('custom.dependent.label', 2)),
        ];
    }

    public function getHeading(): string
    {
        if(isset(request()->route()->parameters()['record']))
        {
            $employee= Employee::find(request()->route()->parameters()['record']);
            $employeeName = $employee->first_name . ' ' . $employee->last_name;
        }
        return isset($employeeName ) ? ucwords(trans_choice('custom.dependent.labelof', 2)) . $employeeName  : ucwords(trans_choice('custom.dependent.label', 2));
    }

     /**
     * Retorna a consulta para a tabela.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getTableQuery(): Builder
    {
        $employeeId = request()->route()->parameters()['record'];    
        $query =  Dependent::query()
            ->where('employee_id', $employeeId);
    
        return $query;
    }
}
