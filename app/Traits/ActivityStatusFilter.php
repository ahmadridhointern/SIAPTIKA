<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * ActivityStatusFilter
 *
 * Shared query logic for filtering activities by computed status.
 * Used by both Admin\ActivityController and Employee\ActivityController.
 *
 * Status logic mirrors Activity::getComputedStatusAttribute():
 *   - scheduled  : activity has not started yet (date+time > now)
 *   - ongoing    : started but no documents uploaded
 *   - completed  : started AND has at least one document
 */
trait ActivityStatusFilter
{
    /**
     * Apply a status filter to an Activity query builder.
     *
     * @param  Builder  $query
     * @param  string   $status  One of: 'scheduled', 'ongoing', 'completed'
     * @param  Carbon   $now     Current timestamp (pass once to avoid clock drift in loops)
     * @return Builder
     */
    protected function applyActivityStatusFilter(Builder $query, string $status, Carbon $now): Builder
    {
        $dateStr = $now->toDateString();
        $timeStr = $now->toTimeString();

        match ($status) {
            'scheduled' => $query->where(function (Builder $q) use ($dateStr, $timeStr) {
                $q->where('activity_date', '>', $dateStr)
                  ->orWhere(function (Builder $q2) use ($dateStr, $timeStr) {
                      $q2->where('activity_date', '=', $dateStr)
                         ->where('time', '>', $timeStr);
                  });
            }),

            'ongoing' => $query->where(function (Builder $q) use ($dateStr, $timeStr) {
                $q->where('activity_date', '<', $dateStr)
                  ->orWhere(function (Builder $q2) use ($dateStr, $timeStr) {
                      $q2->where('activity_date', '=', $dateStr)
                         ->where('time', '<=', $timeStr);
                  });
            })->whereDoesntHave('documents'),

            'completed' => $query->where(function (Builder $q) use ($dateStr, $timeStr) {
                $q->where('activity_date', '<', $dateStr)
                  ->orWhere(function (Builder $q2) use ($dateStr, $timeStr) {
                      $q2->where('activity_date', '=', $dateStr)
                         ->where('time', '<=', $timeStr);
                  });
            })->whereHas('documents'),

            default => $query,
        };

        return $query;
    }
}
