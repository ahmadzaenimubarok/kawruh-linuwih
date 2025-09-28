<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'difficulty_level',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'difficulty_level' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who created this project.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the stages for this project.
     */
    public function stages(): HasMany
    {
        return $this->hasMany(ProjectStage::class)->orderBy('order_no');
    }

    /**
     * Scope a query to only include projects with a specific difficulty level.
     */
    public function scopeByDifficulty($query, string $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    /**
     * Scope a query to only include beginner projects.
     */
    public function scopeBeginner($query)
    {
        return $query->where('difficulty_level', 'beginner');
    }

    /**
     * Scope a query to only include intermediate projects.
     */
    public function scopeIntermediate($query)
    {
        return $query->where('difficulty_level', 'intermediate');
    }

    /**
     * Scope a query to only include advanced projects.
     */
    public function scopeAdvanced($query)
    {
        return $query->where('difficulty_level', 'advanced');
    }
}
