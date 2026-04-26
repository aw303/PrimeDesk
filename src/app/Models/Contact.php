<?php

namespace App\Models;

use App\Models\Concerns\LogsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use LogsAudit;

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'job_title',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'bool',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
