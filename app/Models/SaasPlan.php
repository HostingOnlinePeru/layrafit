<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'duration_months',
        'price',
        'currency',
        'description',
        'badge',
        'features',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_months' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getFeaturesListAttribute(): array
    {
        if (empty($this->features)) {
            return [];
        }

        return array_filter(array_map('trim', explode("\n", str_replace("\r", '', $this->features))));
    }
}
