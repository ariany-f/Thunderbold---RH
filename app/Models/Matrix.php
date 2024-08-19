<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matrix extends Model
{
    use HasFactory;

    // Defina o nome da tabela explicitamente
    protected $table = 'matrix';

    protected $fillable = [
        'name'
    ];

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
}
