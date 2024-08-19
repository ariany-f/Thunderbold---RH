<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use App\Models\User;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $teams = $data['teams'];

        $user = User::find($this->record->id);

        DB::table('team_user')->where('user_id', $user->id)->delete();

        $user->teams()->sync($teams);

        if(empty($data['password']))
        {
            unset($data['password']);
        }
        
        return $data;
    }
}
