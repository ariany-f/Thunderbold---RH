<?php

namespace App\Filament\Employee\Resources\MyTeamResource\Pages;

use App\Filament\Employee\Resources\MyTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMyTeam extends CreateRecord
{
    protected static string $resource = MyTeamResource::class;
}
