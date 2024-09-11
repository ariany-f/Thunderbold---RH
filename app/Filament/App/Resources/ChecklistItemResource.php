<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ChecklistItemResource\Pages;
use App\Filament\App\Resources\ChecklistItemResource\RelationManagers;
use App\Models\ChecklistItem;
use App\Models\Checklist; 
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChecklistItemResource extends Resource
{
    protected static ?string $model = ChecklistItem::class;
    
    protected static bool $shouldRegisterNavigation = false;

    // Se você está usando múltiplos relacionamentos de inquilinos, ajuste o nome da propriedade conforme necessário
    protected static ?string $tenantOwnershipRelationshipName = 'checklist';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Item Name')
                    ->required(),
                Forms\Components\Hidden::make('checklist_id')
                    ->default(request()->query('checklist_id')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                // Tables\Columns\ToggleColumn::make('completed')
                //     ->label('Completed')  
                //     ->beforeStateUpdated(function ($record, $state) {
                //         // Runs before the state is saved to the database.
                //     })
                //     ->afterStateUpdated(function ($record, $state) {
                //         return redirect()->route('checklists.checklist-items', [
                //             'checklist' => $record->checklist_id, // Passando o parâmetro do checklist
                //             'tenant' => Filament::getTenant()->name,
                //         ]);
                //     }),
            ])
            ->filters([
                // Adicione filtros se necessário
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // Adicione a ação de exclusão individual
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChecklistItems::route('/'),
            'create' => Pages\CreateChecklistItem::route('/create'),
            'edit' => Pages\EditChecklistItem::route('/{record}/edit'),
        ];
    }
}
