<?php

namespace App\Filament\Resources\PaySlipResource\Pages;

use App\Filament\Resources\PaySlipResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaySlip extends CreateRecord
{
    protected static string $resource = PaySlipResource::class;
}
