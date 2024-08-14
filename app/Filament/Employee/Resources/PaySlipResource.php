<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\PaySlipResource\Pages;
use App\Filament\Employee\Resources\PaySlipResource\RelationManagers;
use App\Models\PaySlip;
use App\Models\Employee;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class PaySlipResource extends Resource
{
    protected static ?string $model = PaySlip::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    // Use the relationship to determine the tenant context
    protected static ?string $tenantOwnershipRelationshipName = 'employee';

    public static function getNavigationLabel(): string
    {
        return trans_choice('custom.payslip.label', 2);
    }

    public static function getModelLabel(): string
    {
        return trans_choice('custom.payslip.label', 1);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('custom.payslip.details'))
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->relationship(
                                name: 'employee',
                                titleAttribute: 'first_name',
                                modifyQueryUsing: fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant())
                            )
                            ->label(ucwords(trans_choice('custom.employee.label', 1)))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('reference')
                            ->required()
                            ->label('Ref.'),
                        Forms\Components\TextInput::make('process')
                            ->required()
                            ->label('Process'),
                        Forms\Components\TextInput::make('earnings')
                            ->required()
                            ->numeric()
                            ->label('Earnings (R$)'),
                        Forms\Components\TextInput::make('deductions')
                            ->required()
                            ->numeric()
                            ->label('Deductions (R$)'),
                        Forms\Components\TextInput::make('net')
                            ->required()
                            ->numeric()
                            ->label('Net (R$)'),
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
                    ->label('Earnings (R$)')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                Tables\Columns\TextColumn::make('deductions')
                    ->label('Deductions (R$)')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                Tables\Columns\TextColumn::make('net')
                    ->label('Net (R$)')
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
                // Add filters if necessary
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make('Download PDF')
                ->label('PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn($record) => route('payslips.downloadPdf', $record->id)), // Use a URL ao invés de redirecionamento
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ])
            ->emptyStateActions([
                //
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
                            ->label('Earnings (R$)'),
                        TextEntry::make('deductions')
                            ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.'))
                            ->label('Deductions (R$)'),
                        TextEntry::make('net')
                            ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.'))
                            ->label('Net (R$)'),
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
            // Add related managers if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaySlips::route('/')
        ];
    }
}