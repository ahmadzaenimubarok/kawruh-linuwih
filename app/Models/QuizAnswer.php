<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizAnswer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'quiz_answers';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'quiz_id',
        'question_text',
        'options_json',
        'selected_answer',
        'is_correct',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'options_json' => 'array',
        'is_correct' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the quiz that owns this answer.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Scope a query to only include correct answers.
     */
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    /**
     * Scope a query to only include incorrect answers.
     */
    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }

    /**
     * Check if the answer is correct.
     */
    public function isCorrect(): bool
    {
        return $this->is_correct;
    }

    /**
     * Mark the answer as correct.
     */
    public function markAsCorrect(): void
    {
        $this->update(['is_correct' => true]);
    }

    /**
     * Mark the answer as incorrect.
     */
    public function markAsIncorrect(): void
    {
        $this->update(['is_correct' => false]);
    }

    /**
     * Get the options as an array (if multiple choice).
     */
    public function getOptionsAttribute(): ?array
    {
        return $this->options_json;
    }

    /**
     * Set the options as JSON.
     */
    public function setOptionsAttribute(?array $options): void
    {
        $this->options_json = $options;
    }
}
