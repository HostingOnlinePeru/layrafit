<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaffleEligibility extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'consecutive_months_count',
        'is_eligible',
        'last_checked_at',
    ];

    protected function casts(): array
    {
        return [
            'consecutive_months_count' => 'integer',
            'is_eligible' => 'boolean',
            'last_checked_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
