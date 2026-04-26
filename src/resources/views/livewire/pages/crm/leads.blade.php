<?php

use App\Models\Lead;
use App\Models\User;
use App\Services\LeadService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] #[Title('Leads')] class extends Component
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

    public function mount(LeadService $leadService): void
    {
        $this->users = User::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (User $user): array => ['id' => $user->id, 'name' => $user->name])
            ->all();

        $this->reload($leadService);
    }

    public function save(LeadService $leadService): void
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

        $leadService->create($validated);

        $this->reset('title', 'company_name', 'contact_name', 'email', 'phone', 'estimated_value', 'assigned_to');
        $this->status = 'new';
        $this->priority = 'medium';

        $this->reload($leadService);
    }

    public function convert(int $leadId, LeadService $leadService): void
    {
        $lead = Lead::query()->findOrFail($leadId);
        $leadService->convertToCustomer($lead);
        $this->reload($leadService);
    }

    private function reload(LeadService $leadService): void
    {
        $this->leads = $leadService->list()
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
}; ?>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h1 class="text-xl font-semibold">Leads</h1>
            <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-4">
                <x-text-input wire:model="title" placeholder="Lead title" />
                <x-text-input wire:model="company_name" placeholder="Company" />
                <x-text-input wire:model="contact_name" placeholder="Contact person" />
                <x-text-input wire:model="email" placeholder="Email" />
                <x-text-input wire:model="phone" placeholder="Phone" />
                <select wire:model="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="new">New</option>
                    <option value="qualified">Qualified</option>
                    <option value="proposal">Proposal</option>
                    <option value="won">Won</option>
                    <option value="lost">Lost</option>
                </select>
                <select wire:model="priority" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
                <x-text-input wire:model="estimated_value" placeholder="Estimated value" type="number" step="0.01" min="0" />
                <select wire:model="assigned_to" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm md:col-span-2">
                    <option value="">Assign agent (optional)</option>
                    @foreach($users as $user)
                        <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                    @endforeach
                </select>
                <div class="md:col-span-4">
                    <x-primary-button>Create Lead</x-primary-button>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold">Lead List</h2>
            <div class="mt-4 space-y-3">
                @forelse($leads as $lead)
                    <div class="border border-gray-200 rounded-lg p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <p class="font-medium">{{ $lead['title'] }}</p>
                            <p class="text-sm text-gray-500">{{ $lead['company_name'] ?: 'No company' }} | {{ ucfirst($lead['status']) }} | {{ ucfirst($lead['priority']) }}</p>
                            <p class="text-sm text-gray-600 mt-1">Value: ${{ number_format((float) $lead['estimated_value'], 2) }}</p>
                        </div>

                        @if (!$lead['converted_at'] && $lead['status'] !== 'converted')
                            <button class="text-sm px-3 py-2 rounded border border-indigo-200 text-indigo-700 hover:bg-indigo-50" wire:click="convert({{ $lead['id'] }})">
                                Convert to Customer
                            </button>
                        @else
                            <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-800">Converted</span>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No leads yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
