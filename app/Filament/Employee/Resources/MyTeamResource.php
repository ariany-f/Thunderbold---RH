<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\MyTeamResource\Pages;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MyTeamResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return ucwords(trans_choice('custom.my_team.label', 1));
    }

    public static function getModelLabel(): string
    {
        return trans_choice('custom.my_team.label', 1);
    }

    public static function getNavigationBadge(): ?string
    {
        $count = 0;
        if(auth()->user() && auth()->user()->employee_id)
        {
            $count = static::getModel()::where('manager_id', auth()->user()->employee_id)->count();
        }
        return $count;
    }

    public static function getNavigationBadgeColor(): string|array|null
    { 
        $count = 0;
        if(auth()->user() && auth()->user()->employee_id)
        {
            $count = static::getModel()::where('manager_id', auth()->user()->employee_id)->count();
        }
        return $count > 10 ? 'warning' : 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Adicione os campos necessários para o formulário de funcionário
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label(ucwords(__('custom.fields.full_name')))
                    ->sortable()
                    ->searchable(['first_name', 'last_name']),
            ])
            ->filters([
                // Adicione filtros personalizados se necessário
            ])
            ->actions([
               // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->emptyStateActions([
                // Adicione ações para o estado vazio se necessário
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Adicione relações se necessário
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyTeams::route('/'),
        ];
    }

    // Filtra funcionários para mostrar apenas os que têm o manager_id igual ao ID do usuário logado
    public static function getTableQuery(): Builder
    {
        return static::getModel()::where('manager_id', auth()->user()->employee_id);
    }
}
