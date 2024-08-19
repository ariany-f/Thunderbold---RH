<?php

namespace App\Filament\App\Pages\Tenancy;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\FileUpload;

class EditTeamProfile extends EditTenantProfile
{
      public static function getLabel(): string
      {
            return 'Team profile';
      }

      public function form(Form $form): Form
      {
            return $form
                  ->schema([
                        TextInput::make('name'),
                        TextInput::make('slug')
                        ->disabled(), // Torna o campo somente leitura
                        TextInput::make('cnpj')
                        ->label('CNPJ')
                        ->disabled(), // Torna o campo somente leitura
                        FileUpload::make('logo'),
                  ]);
      }
}
