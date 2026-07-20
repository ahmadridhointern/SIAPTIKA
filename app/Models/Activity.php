<?php

namespace App\Models;

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
            'time'          => 'string', // time is stored as time string HH:MM:SS in PostgreSQL
        ];
    }

    /**
     * Relasi Activity belongsTo User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi Activity hasMany Documents.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'activity_id');
    }
}
