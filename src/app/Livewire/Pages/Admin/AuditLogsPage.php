<?php

namespace App\Livewire\Pages\Admin;

use App\Models\AuditLog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Audit Logs')]
class AuditLogsPage extends Component
{
    public array $logs = [];

    public function mount(): void
    {
        $this->logs = AuditLog::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->latest()
            ->limit(100)
            ->get()
            ->map(fn (AuditLog $log): array => [
                'when' => $log->created_at?->toDateTimeString(),
                'action' => $log->action,
                'model' => class_basename($log->auditable_type),
                'id' => $log->auditable_id,
                'user_id' => $log->user_id,
            ])
            ->all();
    }

    public function render()
    {
        return view('livewire.pages.admin.audit-logs');
    }
}
