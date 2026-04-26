<?php

namespace App\Models;

use App\Models\Concerns\LogsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use LogsAudit;

    protected $fillable = [
        'tenant_id',
        'assigned_to',
        'customer_id',
        'title',
        'source',
        'company_name',
        'contact_name',
        'email',
        'phone',
        'status',
        'priority',
        'estimated_value',
        'next_follow_up_at',
        'converted_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'estimated_value' => 'float',
            'next_follow_up_at' => 'datetime',
            'converted_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }
}
