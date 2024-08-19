<?php

namespace App\Filament\Resources\MatrixResources\RelationManagers;

use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeamsRelationManager extends RelationManager
{
    protected static string $relationship = 'teams';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
                Action::make('associate')
                    ->label(__('custom.team.associateexisting'))
                    ->action(function (array $data) {
                        $this->ownerRecord->teams()->attach($data['team_id']);
                    })
                    ->form(function () {
                        // Obtém IDs dos users já vinculados
                        $attachedTeamIds = $this->ownerRecord->teams->pluck('id')->toArray();
                        
                        return [
                            Forms\Components\Select::make('team_id')
                                ->label('Team')
                                ->options(Team::whereNotIn('id', $attachedTeamIds)->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                        ];
                    }),
            ])
            ->actions([
               // Tables\Actions\EditAction::make(),
             //   Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
