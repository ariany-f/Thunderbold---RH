<?php

namespace App\Filament\App\Resources\ChecklistItemResource\Pages;

use App\Filament\App\Resources\ChecklistItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;
use App\Models\Checklist;
use App\Models\ChecklistItem;

class ListChecklistItems extends ListRecords
{
    protected static string $resource = ChecklistItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
     
    /**
     * Retorna a consulta para a tabela.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getTableQuery(): Builder
    {
        $tenantId = Filament::getTenant()->id; // Substitua isso conforme a sua implementação de multi-tenancy
        
        $query =  ChecklistItem::query()
        ->whereIn('checklist_id', function ($query) use ($tenantId) {
            $query->select('id')
                  ->from('checklists')
                  ->where('team_id', $tenantId);
        });
    
        return $query;
    }
}
