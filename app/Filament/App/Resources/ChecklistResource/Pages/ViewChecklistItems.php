<?php

namespace App\Filament\App\Resources\ChecklistResource\Pages;

use App\Filament\App\Resources\ChecklistResource;
use App\Filament\App\Resources\ChecklistItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;
use App\Models\Checklist;
use Illuminate\Support\Facades\Request;
use App\Models\ChecklistItem;

class ViewChecklistItems extends ListRecords
{
    protected static string $resource = ChecklistItemResource::class;

    protected function getHeaderActions(): array
    {
        if (!isset(request()->route()->parameters()['record'])) {
            return array();
        }
        return [
            Actions\CreateAction::make()
                ->url(fn () => route('filament.app.resources.checklist-items.create', [
                    'checklist_id' => request()->route()->parameters()['record'],
                    'tenant' => Filament::getTenant(),
                ])),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.app.resources.checklists.index', ['tenant' => Filament::getTenant()]) => ucwords(trans_choice('custom.checklist.label', 2)),
            ucwords(trans_choice('custom.item.label', 2)),
        ];
    }

    public function getHeading(): string
    {
        if(isset(request()->route()->parameters()['record']))
        {
            $checklist= Checklist::find(request()->route()->parameters()['record']);
            $checklistName = $checklist->name;
        }
        return isset($checklistName ) ? $checklistName  : ucwords(trans_choice('custom.item.label', 2));
    }

     /**
     * Retorna a consulta para a tabela.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getTableQuery(): Builder
    {
        if (!isset(request()->route()->parameters()['record'])) {
            return ChecklistItem::query();
        }
        $checklistId = request()->route()->parameters()['record'];
        $query =  ChecklistItem::query()
            ->where('checklist_id', $checklistId);
    
        return $query;
    }

    public function index()
    {
        // Lógica para exibir os itens da checklist
    }
}
