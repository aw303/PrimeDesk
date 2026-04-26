<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait LogsAudit
{
    public static function bootLogsAudit(): void
    {
        static::created(function ($model): void {
            $model->writeAudit('created', null, $model->getAttributes());
        });

        static::updated(function ($model): void {
            $changes = $model->getChanges();
            unset($changes['updated_at']);

            if ($changes === []) {
                return;
            }

            $model->writeAudit('updated', $model->getOriginal(), $changes);
        });

        static::deleted(function ($model): void {
            $model->writeAudit('deleted', $model->getOriginal(), null);
        });
    }

    private function writeAudit(string $action, ?array $oldValues, ?array $newValues): void
    {
        AuditLog::query()->create([
            'tenant_id' => $this->tenant_id ?? Auth::user()?->tenant_id,
            'user_id' => Auth::id(),
            'auditable_type' => static::class,
            'auditable_id' => $this->getKey(),
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}
