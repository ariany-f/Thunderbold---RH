<?php

namespace App\Filament\Resources\PaySlipResource\Pages;

use App\Filament\Resources\PaySlipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaySlips extends ListRecords
{
    protected static string $resource = PaySlipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
