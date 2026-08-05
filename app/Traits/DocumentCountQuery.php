<?php

namespace App\Traits;

use App\Models\Activity;

/**
 * DocumentCountQuery
 *
 * Shared logic for counting activity documents grouped by type.
 * Used by both Admin\ActivityController::show() and Employee\ActivityController::show().
 */
trait DocumentCountQuery
{
    /**
     * Return an array with document counts per type for the given activity.
     *
     * Executes a single GROUP BY aggregate query instead of N separate COUNT queries.
     *
     * @param  Activity  $activity
     * @return array{all: int, surat: int, notulen: int, dokumentasi: int}
     */
    protected function getDocumentCounts(Activity $activity): array
    {
        $rawCounts = $activity->documents()
            ->selectRaw('document_type, COUNT(*) as aggregate')
            ->groupBy('document_type')
            ->pluck('aggregate', 'document_type');

        return [
            'all'         => (int) $rawCounts->sum(),
            'surat'       => (int) ($rawCounts['surat']       ?? 0),
            'notulen'     => (int) ($rawCounts['notulen']     ?? 0),
            'dokumentasi' => (int) ($rawCounts['dokumentasi'] ?? 0),
        ];
    }
}
