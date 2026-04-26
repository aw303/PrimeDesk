<?php

namespace App\Services;

use App\Models\Deal;
use App\Models\Lead;
use App\Models\WorkflowAutomation;

class WorkflowService
{
    public function trigger(int $tenantId, string $event, array $context = []): void
    {
        $automations = WorkflowAutomation::query()
            ->where('tenant_id', $tenantId)
            ->where('trigger_event', $event)
            ->where('is_active', true)
            ->get();

        foreach ($automations as $automation) {
            $payload = $automation->payload ?? [];

            match ($automation->action_type) {
                'assign_agent' => $this->assignAgent($context, (int) ($payload['user_id'] ?? 0)),
                'mark_high_priority' => $this->markHighPriority($context),
                default => null,
            };
        }
    }

    private function assignAgent(array $context, int $userId): void
    {
        if ($userId <= 0) {
            return;
        }

        if (($context['lead'] ?? null) instanceof Lead) {
            $context['lead']->update(['assigned_to' => $userId]);
        }

        if (($context['deal'] ?? null) instanceof Deal) {
            $context['deal']->update(['owner_id' => $userId]);
        }
    }

    private function markHighPriority(array $context): void
    {
        if (($context['lead'] ?? null) instanceof Lead) {
            $context['lead']->update(['priority' => 'high']);
        }
    }
}
