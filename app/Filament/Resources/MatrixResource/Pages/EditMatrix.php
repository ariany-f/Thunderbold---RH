<?php

namespace App\Filament\Resources\MatrixResource\Pages;

use App\Filament\Resources\MatrixResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMatrix extends EditRecord
{
    protected static string $resource = MatrixResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.matrices.index') => ucwords(trans_choice('custom.matrix.label', 2)),
            'Edit',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
