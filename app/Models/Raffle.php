<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Raffle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'prize',
        'raffle_date',
        'location_id',
        'status',
        'winner_student_id',
    ];

    protected function casts(): array
    {
        return [
            'raffle_date' => 'date',
        ];
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'winner_student_id');
    }
}
