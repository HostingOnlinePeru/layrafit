<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'owner_name',
        'owner_email',
        'owner_phone',
        'logo_url',
        'plan_period',
        'plan_price',
        'status',
        'subscription_starts_at',
        'subscription_ends_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'plan_price' => 'decimal:2',
            'subscription_starts_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(TenantSubscription::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && ($this->subscription_ends_at === null || $this->subscription_ends_at->isFuture());
    }

    public function getPlanPeriodLabelAttribute(): string
    {
        $plan = SaasPlan::where('slug', $this->plan_period)->first();
        if ($plan) {
            return $plan->name.' ($'.number_format($plan->price, 0).')';
        }

        return match ($this->plan_period) {
            '6_months' => '6 Meses ($30)',
            '1_year' => '1 Año ($50)',
            default => $this->plan_period ?? 'Estándar',
        };
    }
}
