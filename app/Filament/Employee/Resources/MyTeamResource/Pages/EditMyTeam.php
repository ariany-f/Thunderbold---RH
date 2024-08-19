<?php

namespace App\Filament\Employee\Resources\MyTeamResource\Pages;

use App\Filament\Employee\Resources\MyTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMyTeam extends EditRecord
{
    protected static string $resource = MyTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }
}
