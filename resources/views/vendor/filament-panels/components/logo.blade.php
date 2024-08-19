@php
    use Filament\Facades\Filament;
    $team = null;
    if(isset(Filament::getTenant()->id))
    {
        $team = \App\Models\Team::find(Filament::getTenant()->id); // Obtenha a empresa do usuário autenticado
    }
    
    $logoUrl = $team && $team->logo ? asset('storage/' . $team->logo) : asset('/images/logo-transparent.png');
@endphp

<img src="{{ $logoUrl }}" alt="Logo" class="h-10">
