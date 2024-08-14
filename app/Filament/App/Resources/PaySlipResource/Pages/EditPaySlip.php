<?php

namespace App\Filament\App\Resources\PaySlipResource\Pages;

use App\Filament\App\Resources\PaySlipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\View\View;
use App\Models\PaySlip;
use Illuminate\Support\Facades\Log;

class EditPaySlip extends EditRecord
{
    protected static string $resource = PaySlipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    // No método getRecord
    public function getRecord(): Model
    {
        Log::info('getRecord method called.');
        $record = parent::getRecord();
        
        // Verifique se o tipo de $record é PaySlip
        if (!$record instanceof PaySlip) {
            Log::error('Record is not an instance of PaySlip.');
            throw new \Exception('Record is not an instance of PaySlip.');
        }
    
        $tenantId = Filament::getTenant()->id;
    
        if ($record->employee->team_id !== $tenantId) {
            Log::error('Unauthorized action: record does not belong to tenant.');
            abort(403, 'Unauthorized action.');
        }
    
        return $record;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $tenantId = Filament::getTenant()->id;
        $paySlip = PaySlip::findOrFail($data['id']);
        
        if ($paySlip->employee->team_id !== $tenantId) {
            abort(403, 'Unauthorized action.');
        }

        return $data;
    }

    protected function getTableQuery(): Builder
    {
        $tenantId = Filament::getTenant()->id;
        
        $query = PaySlip::query()
            ->whereIn('employee_id', function ($query) use ($tenantId) {
                $query->select('id')
                      ->from('employees')
                      ->where('team_id', $tenantId);
            });
    
        return $query;
    }
}
