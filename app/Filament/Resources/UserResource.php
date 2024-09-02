<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\MultiSelect;
use Illuminate\Support\Collection;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function getNavigationLabel(): string
    {
        return ucwords(trans_choice('custom.user.label', 2));
    }

    public static function getModelLabel(): string
    {
        return trans_choice('custom.user.label', 1);
    }

    public static function getNavigationGroup(): string
    {
        return trans_choice('custom.user.management', 2);
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
                    ->label(__('custom.fields.first_name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label(__('custom.fields.email'))
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label(__('custom.fields.password'))
                    ->password()
                    ->required(fn (Get $get) => !$get('id'))
                    ->maxLength(255),
                Forms\Components\Select::make('matrix_id')
                    ->options(function () {
                        return \App\Models\Matrix::all()->pluck('name', 'id')->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->label(ucwords(trans_choice('custom.matrix.label', 1)))
                    ->afterStateUpdated(function ($state, callable $set) {
                        if($state == null) 
                        {
                            $set('teams', []);
                            return;
                        }
                        // Atualiza o campo de times baseado na matriz selecionada
                        $teams = \App\Models\Team::where('matrix_id', $state)->pluck('id')->toArray();
                        $set('teams', $teams);
                    }),
                Forms\Components\MultiSelect::make('teams')
                    ->options(fn (Get $get): Collection => Team::query()
                        ->where('matrix_id', $get('matrix_id'))
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->label(ucwords(trans_choice('custom.team.label', 2)))
                    ->preload()
                    ->live(),
                FileUpload::make('avatar')
                    ->label('Avatar')
                    ->image() // Opcional: para permitir apenas imagens
                    ->disk('public') // Ou outro disco onde você deseja armazenar as imagens
                    ->directory('avatar') // Diretório onde os logos serão armazenados
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('tipo')
                    ->label('Tipo')
                    ->color(fn ($record) => $record->employee_id ? 'warning' : 'primary')
                    ->getStateUsing(fn ($record) =>  $record->employee_id ? ucwords(trans_choice('custom.employee.label', 1)) : 'Usuário do sistema'),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
