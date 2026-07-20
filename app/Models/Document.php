<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'documents';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'activity_id',
        'document_type',
        'file_name',
        'file_url',
    ];

    /**
     * Relasi Document belongsTo Activity.
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }
}
