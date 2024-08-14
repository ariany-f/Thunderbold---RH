<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaySlipResource\Pages;
use App\Filament\Resources\PaySlipResource\RelationManagers;
use App\Models\PaySlip;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PaySlipResource extends Resource
{
    protected static ?string $model = PaySlip::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 2;

    protected static ?string $tenantOwnershipRelationshipName = 'employee';

    public static function getNavigationGroup(): string
    {
        return ucwords(trans_choice('custom.employee.management', 2));
    }

    public static function getNavigationLabel(): string
    {
        return ucwords(trans_choice('custom.payslip.label', 2));
    }

    public static function getModelLabel(): string
    {
        return trans_choice('custom.payslip.label', 1);
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
                Forms\Components\Section::make(__('custom.payslip.details'))
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employee', 'first_name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label(ucwords(trans_choice('custom.employee.label', 1))),
                        Forms\Components\TextInput::make('reference')
                            ->required()
                            ->label('Ref.')
                            ->placeholder('Mar/2024') // Placeholder com o formato desejado
                            ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('M/Y')) // Formata a data para o formato desejado na exibição
                            ->dehydrateStateUsing(fn ($state) => \Carbon\Carbon::createFromFormat('M/Y', $state)->format('Y-m-d')), // Armazena a data no formato padrão
                        Forms\Components\Select::make('process')
                            ->required()
                            ->label('Process')
                            ->options([
                                'FOLHA DE PAGAMENTO' => 'FOLHA DE PAGAMENTO',
                                'DÉCIMO TERCEIRO' => 'DÉCIMO TERCEIRO',
                                'FÉRIAS' => 'FÉRIAS',
                                'ADIANTAMENTO' => 'ADIANTAMENTO',
                                'RESCISÃO' => 'RESCISÃO',
                            ]),
                        Forms\Components\TextInput::make('earnings')
                            ->required()
                            ->numeric()
                            ->label('__('custom.fields.earnings')'),
                        Forms\Components\TextInput::make('deductions')
                            ->required()
                            ->numeric()
                            ->label('__('custom.fields.deductions')'),
                        Forms\Components\TextInput::make('net')
                            ->required()
                            ->numeric()
                            ->label('__('custom.fields.net')'),
                        Forms\Components\TextInput::make('inss_base')
                            ->required()
                            ->numeric()
                            ->label('INSS Base (R$)'),
                        Forms\Components\TextInput::make('irrf_base')
                            ->required()
                            ->numeric()
                            ->label('IRRF Base (R$)'),
                        Forms\Components\TextInput::make('fgts_base')
                            ->required()
                            ->numeric()
                            ->label('FGTS Base (R$)'),
                        Forms\Components\TextInput::make('fgts_deposited')
                            ->required()
                            ->numeric()
                            ->label('FGTS Deposited (R$)'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_name')
                    ->label(ucwords(trans_choice('custom.employee.label', 1)))
                    ->getStateUsing(fn ($record) => "{$record->employee->first_name} {$record->employee->last_name}"),
                Tables\Columns\BadgeColumn::make('reference')
                    ->label('Ref.')
                    ->colors([
                        'primary', // Cor padrão para o badge
                    ]),
                Tables\Columns\BadgeColumn::make('process')
                    ->label('Process')
                    ->colors([
                        'success', // Cor padrão para o badge
                    ]),
                Tables\Columns\TextColumn::make('earnings')
                    ->label('__('custom.fields.earnings')')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                Tables\Columns\TextColumn::make('deductions')
                    ->label('__('custom.fields.deductions')')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                Tables\Columns\TextColumn::make('net')
                    ->label('__('custom.fields.net')')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                Tables\Columns\TextColumn::make('inss_base')
                    ->label('INSS Base (R$)')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                Tables\Columns\TextColumn::make('irrf_base')
                    ->label('IRRF Base (R$)')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                Tables\Columns\TextColumn::make('fgts_base')
                    ->label('FGTS Base (R$)')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                Tables\Columns\TextColumn::make('fgts_deposited')
                    ->label('FGTS Deposited (R$)')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
            ])
            ->filters([
                // Filters can be added here if needed
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('Download PDF')
                ->label('PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn($record) => route('payslips.downloadPdf', $record->id)), // Use a URL ao invés de redirecionamento
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
                Section::make('Pay Slip Info')
                    ->schema([
                        TextEntry::make('employee.first_name')
                            ->label('Employee'),
                        TextEntry::make('reference'),
                        TextEntry::make('process'),
                        TextEntry::make('earnings')
                            ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.'))
                            ->label('__('custom.fields.earnings')'),
                        TextEntry::make('deductions')
                            ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.'))
                            ->label('__('custom.fields.deductions')'),
                        TextEntry::make('net')
                            ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.'))
                            ->label('__('custom.fields.net')'),
                        TextEntry::make('inss_base')
                            ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.'))
                            ->label('INSS Base (R$)'),
                        TextEntry::make('irrf_base')
                            ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.'))
                            ->label('IRRF Base (R$)'),
                        TextEntry::make('fgts_base')
                            ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.'))
                            ->label('FGTS Base (R$)'),
                        TextEntry::make('fgts_deposited')
                            ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.'))
                            ->label('FGTS Deposited (R$)'),
                    ])->columns(2)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add any related managers if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaySlips::route('/'),
            'create' => Pages\CreatePaySlip::route('/create'),
            'edit' => Pages\EditPaySlip::route('/{record}/edit'),
        ];
    }
}
