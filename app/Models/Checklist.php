<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = ['name', 'related_model_type', 'frequency'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function relatedModel()
    {
        return $this->morphTo();
    }

    public function checklistItems()
    {
        return $this->hasMany(ChecklistItem::class);
    }
}
