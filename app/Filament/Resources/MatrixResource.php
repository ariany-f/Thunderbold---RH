<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatrixResource\Pages;
use App\Filament\Resources\MatrixResources\RelationManagers;
use App\Filament\Resources\MatrixResources\RelationManagers\TeamsRelationManager;
use App\Models\Matrix;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MatrixResource extends Resource
{
    protected static ?string $model = Matrix::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return ucwords(trans_choice('custom.matrix.label', 2));
    }

    public static function getModelLabel(): string
    {
        return trans_choice('custom.matrix.label', 1);
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
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('Name')
                ->sortable()
                ->searchable(),
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
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TeamsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMatrices::route('/'),
            'create' => Pages\CreateMatrix::route('/create'),
            'edit' => Pages\EditMatrix::route('/{record}/edit'),
        ];
    }
}
