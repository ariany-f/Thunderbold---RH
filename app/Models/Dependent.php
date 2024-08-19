<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Log; // Para logging

class Dependent extends Model
{
    use HasFactory;

    // Lista de atributos que podem ser preenchidos em massa
    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'relationship',
        'date_of_birth',
    ];

   // Definindo o relacionamento com Employee
   public function employee(): BelongsTo
   {
       return $this->belongsTo(Employee::class, 'employee_id');
   }

   public function getFullNameAttribute()
   {
       return trim($this->first_name . ' ' . $this->last_name);
   }
}
