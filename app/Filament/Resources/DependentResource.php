<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DependentResource\Pages;
use App\Filament\Resources\DependentResource\RelationManagers;
use App\Models\Dependent;
use App\Models\Employee; 
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DependentResource extends Resource
{
    protected static ?string $model = Dependent::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): string
    {
        return ucwords(trans_choice('custom.employee.management', 2));
    }

    public static function getNavigationLabel(): string
    {
        return ucwords(trans_choice('custom.dependent.label', 2));
    }

    public static function getModelLabel(): string
    {
        return trans_choice('custom.dependent.label', 1);
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
                Select::make('employee_id')
                    ->relationship(
                        name: 'employee',
                        titleAttribute: 'first_name',
                        modifyQueryUsing: fn (Builder $query) => $query
                    )
                    ->label(ucwords(trans_choice('custom.employee.label', 1)))
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('first_name')
                    ->label(__('custom.fields.first_name'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('last_name')
                    ->label(__('custom.fields.last_name'))
                    ->required()
                    ->maxLength(255),

                Select::make('relationship')
                    ->label(__('custom.fields.relationship'))
                    ->options([
                        'spouse' => 'Spouse',
                        'child' => 'Child',
                        'parent' => 'Parent',
                        'sibling' => 'Sibling',
                        'other' => 'Other',
                    ])
                    ->required(),
                DatePicker::make('date_of_birth')
                    ->label(__('custom.fields.date_of_birth'))
                    ->required()
                    ->displayFormat('d/m/Y')
                    ->maxDate(now()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_name')
                    ->label(ucwords(trans_choice('custom.employee.label', 1)))
                    ->getStateUsing(fn ($record) => "{$record->employee->first_name} {$record->employee->last_name}")
                    ->toggleable(isToggledHiddenByDefault: isset(request()->route()->parameters()['record']) ? true : false),
                Tables\Columns\TextColumn::make('employee.team.name')
                    ->label(ucwords(trans_choice('custom.team.label', 1)))
                    ->searchable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label(ucwords(__('custom.fields.full_name')))
                    ->sortable()
                    ->searchable(['first_name', 'last_name']),
                Tables\Columns\TextColumn::make('relationship')->label('Relationship'),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Date of Birth')
                    ->dateTime('d/m/Y'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDependents::route('/'),
            'create' => Pages\CreateDependent::route('/create'),
            'edit' => Pages\EditDependent::route('/{record}/edit'),
        ];
    }
}
