<?php

namespace App\Models;

use App\Models\Concerns\LogsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowAutomation extends Model
{
    use LogsAudit;

    protected $fillable = [
        'tenant_id',
        'name',
        'trigger_event',
        'action_type',
        'conditions',
        'payload',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'conditions' => 'array',
            'payload' => 'array',
            'is_active' => 'bool',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
