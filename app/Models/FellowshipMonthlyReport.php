<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FellowshipMonthlyReport extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'report_date' => 'date',
        'metrics_summary' => 'array',
        'photos' => 'array',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function getStatusLabelMarathiAttribute(): string
    {
        return match ($this->status) {
            'submitted' => 'सादर केले (Submitted)',
            'approved' => 'तपासले व मंजूर (Approved)',
            default => 'मसुदा (Draft)',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'submitted' => 'text-bg-primary',
            'approved' => 'text-bg-success',
            default => 'text-bg-warning',
        };
    }

    public function getMentorStatusBadgeClassAttribute(): string
    {
        return match ($this->mentor_status) {
            'approved' => 'text-bg-success',
            'revisions_requested' => 'text-bg-danger',
            default => 'text-bg-secondary',
        };
    }
}
