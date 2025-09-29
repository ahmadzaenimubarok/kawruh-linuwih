<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentProjectStage extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'student_project_stages';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'student_project_id',
        'stage_id',
        'status',
        'submission_link',
        'feedback',
        'reviewed_by',
        'reviewed_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the student project that owns this stage.
     */
    public function studentProject(): BelongsTo
    {
        return $this->belongsTo(StudentProject::class);
    }

    /**
     * Get the project stage.
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(ProjectStage::class);
    }

    /**
     * Get the user who reviewed this stage.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope a query to only include not started stages.
     */
    public function scopeNotStarted($query)
    {
        return $query->where('status', 'not_started');
    }

    /**
     * Scope a query to only include in progress stages.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope a query to only include submitted stages.
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope a query to only include reviewed stages.
     */
    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    /**
     * Scope a query to only include completed stages.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Mark the stage as started.
     */
    public function markAsStarted(): void
    {
        $this->update([
            'status' => 'in_progress',
        ]);
    }

    /**
     * Submit the stage with a submission link.
     */
    public function submit(string $submissionLink): void
    {
        $this->update([
            'status' => 'submitted',
            'submission_link' => $submissionLink,
        ]);
    }

    /**
     * Review the stage with feedback.
     */
    public function review(string $feedback, int $reviewerId): void
    {
        $this->update([
            'status' => 'reviewed',
            'feedback' => $feedback,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Mark the stage as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
        ]);
    }

    /**
     * Check if the stage is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the stage is submitted.
     */
    public function isSubmitted(): bool
    {
        return in_array($this->status, ['submitted', 'reviewed', 'completed']);
    }

    /**
     * Check if the stage needs review.
     */
    public function needsReview(): bool
    {
        return $this->status === 'submitted';
    }
}
