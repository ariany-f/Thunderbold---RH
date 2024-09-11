<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['subordinates_count'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    } 

    public function checklists()
    {
        return $this->morphMany(Checklist::class, 'related_model');
    }
    
    public function paySlips(): hasMany
    {
        return $this->hasMany(PaySlip::class);
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
    
    public function dependents(): HasMany
    {
        return $this->hasMany(Dependent::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    public function getSubordinatesCountAttribute()
    {
        return $this->subordinates()->count();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($employee) {
            // Verifica se o Employee tem um User associado e o exclui
            if ($employee->user) {
                // Exclui o usuário associado
                $employee->user->delete(); // Exclui fisicamente
            }
        });
    }
}
