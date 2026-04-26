<?php

namespace App\Livewire\Pages\Crm;

use App\Models\Lead;
use App\Models\User;
use App\Services\LeadService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Leads')]
class LeadsPage extends Component
{
    public string $title = '';
    public string $company_name = '';
    public string $contact_name = '';
    public string $email = '';
    public string $phone = '';
    public string $status = 'new';
    public string $priority = 'medium';
    public string $estimated_value = '0';
    public string $assigned_to = '';

    public array $leads = [];
    public array $users = [];

    public function mount(): void
    {
        $this->users = User::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (User $user): array => ['id' => $user->id, 'name' => $user->name])
            ->all();

        $this->reload();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', 'in:new,qualified,proposal,won,lost,converted'],
            'priority' => ['required', 'in:low,medium,high'],
            'estimated_value' => ['required', 'numeric', 'min:0'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        app(LeadService::class)->create($validated);

        $this->reset('title', 'company_name', 'contact_name', 'email', 'phone', 'estimated_value', 'assigned_to');
        $this->status = 'new';
        $this->priority = 'medium';

        $this->reload();
    }

    public function convert(int $leadId): void
    {
        $lead = Lead::query()->findOrFail($leadId);
        app(LeadService::class)->convertToCustomer($lead);
        $this->reload();
    }

    public function render()
    {
        return view('livewire.pages.crm.leads');
    }

    private function reload(): void
    {
        $this->leads = app(LeadService::class)
            ->list()
            ->map(fn (Lead $lead): array => [
                'id' => $lead->id,
                'title' => $lead->title,
                'company_name' => $lead->company_name,
                'status' => $lead->status,
                'priority' => $lead->priority,
                'estimated_value' => $lead->estimated_value,
                'converted_at' => $lead->converted_at?->toDateTimeString(),
            ])
            ->all();
    }
}
