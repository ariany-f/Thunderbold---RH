<?php

namespace App\Filament\App\Widgets;

use App\Models\Employee;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class EmployeeAppChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected static string $color = 'warning';

    public function getHeading(): string
    {
        return ucwords(trans_choice('custom.employee.chart', 2));
    }

    protected function getData(): array
    {
        $data = Trend::query(Employee::query()->whereBelongsTo(Filament::getTenant()))
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => ucwords(trans_choice('custom.employee.label', 2)),
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
