<?php

namespace App\Models;

use App\Models\Concerns\LogsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntegrationConnection extends Model
{
    use LogsAudit;

    protected $fillable = [
        'tenant_id',
        'provider',
        'name',
        'credentials',
        'is_active',
        'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'credentials' => 'array',
            'is_active' => 'bool',
            'last_synced_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
