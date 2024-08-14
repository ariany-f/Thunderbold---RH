<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Team;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsAdminOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users', User::query()->count())
                ->label(ucwords(trans_choice('custom.user.label', 2)))
                ->description(__('custom.user.all_from_database'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Teams', Team::query()->count())
                ->label(ucwords(trans_choice('custom.team.label', 2)))
                ->description(__('custom.team.all_from_database'))
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
            Stat::make('Employees', Employee::query()->count())
                ->label(ucwords(trans_choice('custom.employee.label', 2)))
                ->description(__('custom.employee.all_from_database'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
