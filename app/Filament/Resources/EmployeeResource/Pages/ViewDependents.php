<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Filament\Resources\DependentResource;
use App\Models\Employee;
use App\Models\Dependent;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ViewDependents extends ListRecords
{
    protected static string $resource = DependentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->url(fn () => route('filament.admin.resources.dependents.create', [
                    'employee_id' => request()->route()->parameters()['record']
                ])),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.employees.index') => ucwords(trans_choice('custom.employee.label', 2)),
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
