<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillProgress extends Model
{
    protected $table = 'skill_progress';
    protected $fillable = ['skill_id', 'level', 'logged_date', 'notes'];

    protected $casts = [
        'logged_date' => 'date',
    ];

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }
}
