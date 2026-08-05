<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'activities';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'title',
        'activity_date',
        'time',
        'location',
        'description',
        'status',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'time'          => 'string', // stored as HH:MM:SS in PostgreSQL
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'activity_id');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Computed Attributes
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Build the full Carbon datetime for when this activity starts.
     * Shared by is_started and computed_status to avoid parsing twice.
     */
    private function buildActivityDateTime(): Carbon
    {
        // Normalize HH:MM → HH:MM:SS
        $timeString = strlen($this->time) === 5 ? $this->time . ':00' : $this->time;

        return Carbon::parse($this->activity_date->toDateString() . ' ' . $timeString);
    }

    /**
     * Check if activity start time has arrived / passed.
     *
     * @return bool
     */
    public function getIsStartedAttribute(): bool
    {
        return now()->greaterThanOrEqualTo($this->buildActivityDateTime());
    }

    /**
     * Get computed status text based on business rules:
     *   - 'Direncana'        : not started yet (now < activity_date + time)
     *   - 'Sudah Berlangsung': started but no archive documents uploaded
     *   - 'Selesai'          : started AND has at least one document
     *
     * Optimised: reuses buildActivityDateTime() and avoids extra query when
     * documents_count is already eager-loaded via withCount('documents').
     *
     * @return string
     */
    public function getComputedStatusAttribute(): string
    {
        if (!$this->getIsStartedAttribute()) {
            return 'Direncana';
        }

        // Prefer the pre-loaded count (set by withCount) to avoid N+1
        $hasDocuments = isset($this->attributes['documents_count'])
            ? (int) $this->documents_count > 0
            : $this->documents()->exists();

        return $hasDocuments ? 'Selesai' : 'Sudah Berlangsung';
    }

    /**
     * Get URL-safe status code: 'scheduled', 'ongoing', or 'completed'.
     *
     * @return string
     */
    public function getStatusCodeAttribute(): string
    {
        return match ($this->computed_status) {
            'Direncana'        => 'scheduled',
            'Sudah Berlangsung' => 'ongoing',
            default            => 'completed',
        };
    }
}
