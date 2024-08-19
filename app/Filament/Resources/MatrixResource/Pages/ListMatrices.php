<?php

namespace App\Filament\Resources\MatrixResource\Pages;

use App\Filament\Resources\MatrixResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMatrices extends ListRecords
{
    protected static string $resource = MatrixResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.matrices.index') => ucwords(trans_choice('custom.matrix.label', 2)),
            'List',
        ];
    }

    public function getHeading(): string
    {
        return ucwords(trans_choice('custom.matrix.label', 2));
    }
}
