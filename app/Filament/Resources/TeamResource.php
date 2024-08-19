<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Filament\Resources\TeamResource\RelationManagers;
use App\Filament\Resources\TeamResource\RelationManagers\UsersRelationManager;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?int $navigationSort = 6;

    public static function getNavigationLabel(): string
    {
        return ucwords(trans_choice('custom.team.label', 2));
    }

    public static function getModelLabel(): string
    {
        return trans_choice('custom.team.label', 1);
    }

    public static function getNavigationGroup(): string
    {
        return trans_choice('custom.system.management', 1);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'warning' : 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Team Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('cnpj')
                        ->required()
                        ->label('CNPJ'),
                    Forms\Components\Select::make('matrix_id')
                        ->label(ucwords(trans_choice('custom.matrix.label', 1)))
                        ->relationship('matrix', 'name'),
                    FileUpload::make('logo')
                        ->label('Logo')
                        ->image() // Opcional: para permitir apenas imagens
                        ->disk('public') // Ou outro disco onde você deseja armazenar as imagens
                        ->directory('logos') // Diretório onde os logos serão armazenados
                        ->columnSpan('full'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('matrix_name')
                ->label(ucwords(trans_choice('custom.matrix.label', 1)))
                ->sortable()
                ->searchable()
                ->getStateUsing(fn ($record) => $record->matrix ? $record->matrix->name : '<span style="color: lightgrey">Sem matriz associada</span>')
                ->formatStateUsing(function ($state) {
                    if ($state) {
                        return $state;
                    }
                })
                ->html(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cnpj')
                    ->label('CNPJ'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }    
}
