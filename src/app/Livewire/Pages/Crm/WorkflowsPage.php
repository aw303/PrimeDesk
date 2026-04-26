<?php

namespace App\Livewire\Pages\Crm;

use App\Models\WorkflowAutomation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Workflows')]
class WorkflowsPage extends Component
{
    public string $name = '';
    public string $trigger_event = 'lead.created';
    public string $action_type = 'assign_agent';
    public string $payload_user_id = '';

    public array $workflows = [];

    public function mount(): void
    {
        $this->reload();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'trigger_event' => ['required', 'in:lead.created,deal.closed'],
            'action_type' => ['required', 'in:assign_agent,mark_high_priority'],
            'payload_user_id' => ['nullable', 'exists:users,id'],
        ]);

        WorkflowAutomation::query()->create([
            'tenant_id' => auth()->user()->tenant_id,
            'name' => $validated['name'],
            'trigger_event' => $validated['trigger_event'],
            'action_type' => $validated['action_type'],
            'payload' => [
                'user_id' => $validated['payload_user_id'] ?: null,
            ],
            'is_active' => true,
        ]);

        $this->reset('name', 'payload_user_id');
        $this->trigger_event = 'lead.created';
        $this->action_type = 'assign_agent';

        $this->reload();
    }

    public function toggle(int $workflowId): void
    {
        $workflow = WorkflowAutomation::query()->findOrFail($workflowId);
        $workflow->update(['is_active' => ! $workflow->is_active]);

        $this->reload();
    }

    public function render()
    {
        return view('livewire.pages.crm.workflows');
    }

    private function reload(): void
    {
        $this->workflows = WorkflowAutomation::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->latest()
            ->get()
            ->map(fn (WorkflowAutomation $workflow): array => [
                'id' => $workflow->id,
                'name' => $workflow->name,
                'trigger_event' => $workflow->trigger_event,
                'action_type' => $workflow->action_type,
                'is_active' => $workflow->is_active,
            ])
            ->all();
    }
}
