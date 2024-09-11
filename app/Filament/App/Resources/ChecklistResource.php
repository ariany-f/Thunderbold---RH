<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ChecklistResource\Pages;
use App\Filament\App\Resources\ChecklistResource\RelationManagers;
use App\Models\Checklist;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Facades\Filament;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\City;
use App\Models\Employee;
use App\Models\User;
use App\Models\Team;
use App\Models\State;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\Select;
use Filament\Infolists\Infolist;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Collection;

class ChecklistResource extends Resource
{
    protected static ?string $model = Checklist::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Checklist Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('related_model_type')
                    ->label('Related Model Type')
                    ->options([
                        'App\Models\Employee' => 'Employee'
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Limpar o ID relacionado quando o tipo mudar
                        $set('related_model_id', null);
                    }),

                Forms\Components\Select::make('frequency')
                    ->label('Frequency')
                    ->options([
                        '' => 'No Frequency',
                        'daily' => 'Daily',
                        'weekly' => 'Weekly',
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                        // Adicione outras frequências conforme necessário
                    ])
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Checklist Name'),

                Tables\Columns\TextColumn::make('related_model_type')
                    ->label('Related Model Type'),

                Tables\Columns\TextColumn::make('frequency')
                    ->label('Frequency'),
            ])
            ->filters([
                // Adicione filtros se necessário
            ])
            ->actions([ 
                Action::make('viewItems')
                    ->label(ucwords(trans_choice('custom.item.label', 2)))
                    ->icon('heroicon-o-check-circle')
                    ->url(fn($record) => route('checklists.checklist-items', [
                        'checklist' => $record->id, 
                        'tenant' => Filament::getTenant()->name
                    ])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChecklists::route('/'),
            'create' => Pages\CreateChecklist::route('/create'),
            'edit' => Pages\EditChecklist::route('/{record}/edit'),
            'checklist-items' => Pages\ViewChecklistItems::route('/{record}/checklist-items'),
        ];
    }
}
