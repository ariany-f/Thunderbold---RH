<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\PaySlipResource\Pages;
use App\Filament\Employee\Resources\PaySlipResource\RelationManagers;
use App\Models\PaySlip;
use App\Models\Employee;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Carbon\Carbon;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\Select;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Filament\Infolists\Components\Tabs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;

class PaySlipResource extends Resource
{
    protected static ?string $model = PaySlip::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    // Use the relationship to determine the tenant context
    protected static ?string $tenantOwnershipRelationshipName = 'employee';

    public static function getNavigationLabel(): string
    {
        return ucwords(trans_choice('custom.payslip.label', 2));
    }

    public static function getModelLabel(): string
    {
        return trans_choice('custom.payslip.label', 1);
    }

    public static function form(Form $form): Form
    { 
       // Load month names from translations
        $monthsInPortuguese = Lang::get('months');

        // Generate month/year options in Portuguese
        $monthYearOptions = [];
        $now = Carbon::now();

        for ($i = 0; $i < 12; $i++) {
            $date = $now->subMonths($i);
            $month = $monthsInPortuguese[$date->month];
            $monthYear = "{$month}/{$date->year}";
            $monthYearOptions[$monthYear] = $monthYear;
        }

        // Restore the $now object to its original state
        $now = Carbon::now();

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
                        Forms\Components\Select::make('reference')
                            ->required()  // Marca o campo como obrigatório
                            ->label('Ref.')
                            ->placeholder('Mar/2024')
                            ->options($monthYearOptions) // Define as opções para seleção
                            ->searchable() // Permite a busca
                            ->preload() // Carrega as opções antecipadamente
                            ->formatStateUsing(fn ($state) => $state) // Exibe o valor como está
                            ->dehydrateStateUsing(fn ($state) => $state), // Garante que o valor seja armazenado corretamente
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
                            ->label(__('custom.fields.earnings')),
                        Forms\Components\TextInput::make('deductions')
                            ->required()
                            ->numeric()
                            ->label(__('custom.fields.deductions')),
                        Forms\Components\TextInput::make('net')
                            ->required()
                            ->numeric()
                            ->label(__('custom.fields.net')),
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
                    ->label(__('custom.fields.earnings'))
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                Tables\Columns\TextColumn::make('deductions')
                    ->label(__('custom.fields.deductions'))
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                Tables\Columns\TextColumn::make('net')
                    ->label(__('custom.fields.net'))
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
                Section::make($infolist->record->process)
                    ->description(new HtmlString('<div class="flex items-center">
                        <span class="text-sm font-medium text-gray-700">Referencia: </span>
                        <x-filament::badge color="infdangero">
                            ' . ($infolist->record->reference ?? 'N/A') . '
                        </x-filament::badge>
                    </div>'))
                    ->schema([
                        Tabs::make('Tabs')
                            ->tabs([
                                Tabs\Tab::make(ucwords(trans_choice('custom.employee.label', 1)))
                                    ->schema([
                                        TextEntry::make('employee.fullname')
                                            ->label(ucwords(__('custom.fields.full_name')))
                                            ->columnSpan(2),
                                    ]),
                                Tabs\Tab::make(ucwords(trans_choice('custom.payslip.label', 1)))
                                    ->schema([
                                        TextEntry::make('earnings')
                                            ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.'))
                                            ->label(__('custom.fields.earnings')),
                                        TextEntry::make('deductions')
                                            ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.'))
                                            ->label(__('custom.fields.deductions')),
                                        TextEntry::make('net')
                                            ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.'))
                                            ->label(__('custom.fields.net')),
                                        TextEntry::make('inss_base')
                                            ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.'))
                                            ->label('INSS Base (R$)'),
                                        TextEntry::make('irrf_base')
                                            ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.'))
                                            ->label('IRRF Base (R$)'),
                                        TextEntry::make('fgts_base')
                                            ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.'))
                                            ->label('FGTS Base (R$)'),
                                        TextEntry::make('fgts_deposited')
                                            ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.'))
                                            ->label('FGTS Deposited (R$)'),
                                    ])
                                    ->columns(3),
                            ]),
                    ]),
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