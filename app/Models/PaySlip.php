<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Log; // Para logging

class PaySlip extends Model
{
    use HasFactory;

    // Defina o nome da tabela explicitamente
    protected $table = 'payslips';

    protected $fillable = [
        'employee_id',
        'reference',
        'process',
        'earnings',
        'deductions',
        'net',
        'inss_base',
        'irrf_base',
        'fgts_base',
        'fgts_deposited',
    ];

   // Definindo o relacionamento com Employee
   public function employee(): BelongsTo
   {
       return $this->belongsTo(Employee::class, 'employee_id');
   }
}
