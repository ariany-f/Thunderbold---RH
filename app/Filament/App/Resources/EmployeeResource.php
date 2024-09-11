<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\EmployeeResource\Pages;
use App\Filament\App\Resources\EmployeeResource\RelationManagers;
use App\Models\City;
use App\Models\Employee;
use App\Models\User;
use App\Models\Team;
use App\Models\State;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\Action;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\Select;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): string
    {
        return ucwords(trans_choice('custom.employee.label', 2));
    }

    public static function getNavigationLabel(): string
    {
        return ucwords(trans_choice('custom.employee.label', 2));
    }

    public static function getModelLabel(): string
    {
        return trans_choice('custom.employee.label', 1);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(ucwords(trans_choice('custom.relationship.label', 1)))
                    ->schema([
                        Forms\Components\Select::make('country_id')
                            ->label(ucwords(trans_choice('custom.country.label', 1)))
                            ->relationship(name: 'country', titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('state_id', null);
                                $set('city_id', null);
                            })
                            ->required(),
                        Forms\Components\Select::make('state_id')
                            ->label(ucwords(trans_choice('custom.state.label', 1)))
                            ->options(fn (Get $get): Collection => State::query()
                                ->where('country_id', $get('country_id'))
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('city_id', null))
                            ->required(),
                        Forms\Components\Select::make('city_id')
                            ->label(ucwords(trans_choice('custom.city.label', 1)))
                            ->options(fn (Get $get): Collection => City::query()
                                ->where('state_id', $get('state_id'))
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('department_id')
                            ->label(ucwords(trans_choice('custom.department.label', 1)))
                            ->relationship(
                                name: 'department',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant())
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('team_id')
                            ->label(ucwords(trans_choice('custom.team.label', 1)))
                            ->options(Team::all()->pluck('name', 'id'))
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('manager_id', null);
                            }),
                        Select::make('manager_id')
                            ->label(ucwords(trans_choice('custom.manager.label', 1)))
                            ->options(function (callable $get) {
                                $teamId = $get('team_id');
                                $employeeId = request()->route('record');
                                if (!$teamId) {
                                    return [];
                                }
                        
                                $employees = Employee::where('team_id', $teamId)->where('id', '!=', $employeeId)->get();
                                return $employees->pluck('full_name', 'id');
                            })
                            ->placeholder('Sem ' . ucwords(trans_choice('custom.manager.label', 1)))
                            ->nullable(),
                    ])->columns(2),
                Forms\Components\Section::make(ucwords(trans_choice('custom.user.label', 1)))
                    ->description('Put the user name details in.')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ])->columns(3),
                Forms\Components\Section::make(ucwords(__('custom.fields.bank.account')))  // Nova seção para detalhes da conta
                    ->schema([
                        Forms\Components\TextInput::make('bank')
                            ->label(ucwords(trans_choice('custom.fields.bank.label', 1)))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('agency')
                            ->label(ucwords(trans_choice('custom.fields.bank.agency', 1)))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('account')
                            ->label(ucwords(trans_choice('custom.fields.bank.account_number', 1)))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('salary_base')
                            ->label(ucwords(trans_choice('custom.fields.bank.salary_base', 1)))
                            ->numeric()
                            ->maxLength(10),
                    ])->columns(2),
                Forms\Components\Section::make(ucwords(__('custom.fields.address')))
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->label(ucwords(__('custom.fields.address')))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('zip_code')
                            ->label(ucwords(__('custom.fields.zip_code')))
                            ->maxLength(255),
                    ])->columns(2),
                Forms\Components\Section::make(ucwords(__('custom.fields.dates')))
                    ->schema([
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label(ucwords(__('custom.fields.date_of_birth')))
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->required(),
                        Forms\Components\DatePicker::make('date_hired')
                            ->label(ucwords(__('custom.fields.date_hired')))
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->required(),
                    ])->columns(2)

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
                Tables\Columns\BadgeColumn::make('subordinates_count')
                    ->label('Tipo')
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'primary')
                    ->formatStateUsing(function ($state) {
                        return $state > 0 ? ucwords(trans_choice('custom.manager.label', 1)) : ucwords(trans_choice('custom.employee.label', 1));
                    }),
                Tables\Columns\TextColumn::make('manager.full_name')
                    ->label(ucwords(trans_choice('custom.manager.label', 1)))
                    ->formatStateUsing(fn ($state) => $state ?? 'Nenhum'),
                Tables\Columns\TextColumn::make('address')
                    ->label(ucwords(__('custom.fields.address')))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('zip_code')
                    ->label(ucwords(__('custom.fields.zip_code')))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label(ucwords(__('custom.fields.date_of_birth')))
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_hired')
                    ->label(ucwords(__('custom.fields.date_hired')))
                    ->dateTime('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(ucwords(__('custom.fields.created_at')))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(ucwords(__('custom.fields.updated_at')))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('Department')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->label(__('custom.department.filter'))
                    ->indicator('Department'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
                ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make('viewDependents')
                    ->label(ucwords(trans_choice('custom.dependent.label', 2)))
                    ->icon('heroicon-o-users')
                    ->url(fn($record) => route('employees.dependents', [
                        'employee' => $record->id, 
                        'tenant' => Filament::getTenant()->name
                    ])),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Relationships')
                    ->schema([
                        TextEntry::make('country.name'),
                        TextEntry::make('state.name'),
                        TextEntry::make('city.name'),
                        TextEntry::make('department.name'),
                    ])->columns(2),
                Section::make('Name')
                    ->schema([
                        TextEntry::make('first_name'),
                        TextEntry::make('last_name'),
                    ])->columns(3),
                Section::make('Address')
                    ->schema([
                        TextEntry::make('address'),
                        TextEntry::make('zip_code'),
                    ])->columns(2),
                Section::make('Manager') // Nova seção para o gerente
                    ->schema([
                        TextEntry::make('manager.full_name') // Exibindo o nome do gerente
                            ->label('Manager')
                            ->default('No manager assigned') // Valor padrão se não houver gerente
                    ])->columns(2),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
            'dependents' => Pages\ViewDependents::route('/{record}/dependents'),
        ];
    }
}
