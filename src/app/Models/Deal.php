<?php

namespace App\Models;

use App\Models\Concerns\LogsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deal extends Model
{
    use LogsAudit;

    protected $fillable = [
        'tenant_id',
        'lead_id',
        'customer_id',
        'owner_id',
        'name',
        'stage',
        'status',
        'value',
        'probability',
        'expected_close_date',
        'closed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'float',
            'expected_close_date' => 'date',
            'closed_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
