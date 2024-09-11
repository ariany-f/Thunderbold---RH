<?php

namespace App\Filament\App\Resources\ChecklistItemResource\Pages;

use App\Filament\App\Resources\ChecklistItemResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateChecklistItem extends CreateRecord
{
    protected static string $resource = ChecklistItemResource::class;
    
    public function mount(): void
    {
        // Passa o parâmetro `checklist_id` para a URL
        $checklistId = request()->query('checklist_id');
        $this->form->fill([
            'checklist_id' => $checklistId,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        // Obtém o ID do checklist da instância criada
        $checklistId = $this->record->checklist_id; // Assumindo que 'checklist_id' está disponível no modelo

        // Se 'checklist_id' não estiver disponível diretamente, você pode recuperá-lo da seguinte forma:
        if (!$checklistId) {
            $checklistId = request()->input('checklist_id'); // Como alternativa, pega o ID da query string
        }

        // Obtém o nome do tenant de forma adequada
        $tenant = Filament::getTenant()->name; // Ajuste conforme necessário

        // Retorna a URL de redirecionamento para a página de listagem de checklist items
        return route('checklists.checklist-items', [
            'checklist' => $checklistId,
            'tenant' => $tenant,
        ]);
    }
}
