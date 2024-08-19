<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'cnpj', 'logo', 'email'];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    // Definição do relacionamento com PaySlip
    public function paySlips(): HasManyThrough
    {
        return $this->hasManyThrough(PaySlip::class, Employee::class, 'team_id', 'employee_id');
    }

    // Definição do relacionamento com dependents
    public function dependents(): HasManyThrough
    {
        return $this->hasManyThrough(Dependent::class, Employee::class, 'team_id', 'employee_id');
    }
}
