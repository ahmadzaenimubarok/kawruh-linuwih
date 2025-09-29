<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'quizzes';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'student_project_stage_id',
        'user_id',
        'score',
        'total_questions',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'score' => 'integer',
        'total_questions' => 'integer',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the student project stage that owns this quiz.
     */
    public function studentProjectStage(): BelongsTo
    {
        return $this->belongsTo(StudentProjectStage::class);
    }

    /**
     * Get the user who took this quiz.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the answers for this quiz.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }

    /**
     * Scope a query to only include completed quizzes.
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    /**
     * Scope a query to only include incomplete quizzes.
     */
    public function scopeIncomplete($query)
    {
        return $query->whereNull('completed_at');
    }

    /**
     * Calculate the percentage score.
     */
    public function getPercentageAttribute(): float
    {
        if ($this->total_questions === 0) {
            return 0;
        }

        return round(($this->score / $this->total_questions) * 100, 2);
    }

    /**
     * Check if the quiz is completed.
     */
    public function isCompleted(): bool
    {
        return !is_null($this->completed_at);
    }

    /**
     * Mark the quiz as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'completed_at' => now(),
        ]);
    }

    /**
     * Check if the quiz is passed (assuming 70% is passing).
     */
    public function isPassed(int $passingScore = 70): bool
    {
        return $this->percentage >= $passingScore;
    }
}
