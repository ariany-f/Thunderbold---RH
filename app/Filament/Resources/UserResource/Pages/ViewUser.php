<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use App\Models\User;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }


    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Obter o usuário atual e seus times
        $user = User::find($this->record->id);

        // Se a matriz do usuário é a mesma para todos os times
        $matrixId = $user->teams->first()->matrix_id ?? null;
        
        // Preencher a matriz selecionada, se aplicável
        $data['matrix_id'] = $matrixId;

        // Garantir que 'teams' no formulário contenha IDs dos times
        $data['teams'] = $user->teams->pluck('id')->toArray();

        return $data;
    }
}
