<?php

use App\Models\Customer;
use App\Models\Deal;
use App\Models\Lead;
use App\Services\DealService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] #[Title('Deals')] class extends Component
{
    public string $name = '';
    public string $value = '0';
    public string $stage = 'prospecting';
    public string $probability = '10';
    public string $status = 'open';
    public string $lead_id = '';
    public string $customer_id = '';
    public string $expected_close_date = '';

    public array $deals = [];
    public array $leads = [];
    public array $customers = [];

    public function mount(DealService $dealService): void
    {
        $tenantId = auth()->user()->tenant_id;

        $this->leads = Lead::query()->where('tenant_id', $tenantId)->orderBy('title')->get(['id', 'title'])
            ->map(fn (Lead $lead): array => ['id' => $lead->id, 'name' => $lead->title])->all();

        $this->customers = Customer::query()->where('tenant_id', $tenantId)->orderBy('company_name')->get(['id', 'company_name'])
            ->map(fn (Customer $customer): array => ['id' => $customer->id, 'name' => $customer->company_name])->all();

        $this->reload($dealService);
    }

    public function save(DealService $dealService): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'numeric', 'min:0'],
            'stage' => ['required', 'in:prospecting,qualified,proposal,negotiation,closed'],
            'probability' => ['required', 'integer', 'between:0,100'],
            'status' => ['required', 'in:open,won,lost'],
            'lead_id' => ['nullable', 'exists:leads,id'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'expected_close_date' => ['nullable', 'date'],
        ]);

        $dealService->create($validated);

        $this->reset('name', 'value', 'lead_id', 'customer_id', 'expected_close_date');
        $this->stage = 'prospecting';
        $this->probability = '10';
        $this->status = 'open';

        $this->reload($dealService);
    }

    public function markWon(int $dealId, DealService $dealService): void
    {
        $dealService->updateStage(Deal::query()->findOrFail($dealId), 'closed', 'won');
        $this->reload($dealService);
    }

    public function markLost(int $dealId, DealService $dealService): void
    {
        $dealService->updateStage(Deal::query()->findOrFail($dealId), 'closed', 'lost');
        $this->reload($dealService);
    }

    private function reload(DealService $dealService): void
    {
        $this->deals = $dealService->list()
            ->map(fn (Deal $deal): array => [
                'id' => $deal->id,
                'name' => $deal->name,
                'stage' => $deal->stage,
                'status' => $deal->status,
                'value' => $deal->value,
                'probability' => $deal->probability,
            ])
            ->all();
    }
}; ?>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h1 class="text-xl font-semibold">Deals / Opportunities</h1>
            <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-4">
                <x-text-input wire:model="name" placeholder="Deal name" />
                <x-text-input wire:model="value" type="number" step="0.01" min="0" placeholder="Value" />
                <select wire:model="stage" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="prospecting">Prospecting</option>
                    <option value="qualified">Qualified</option>
                    <option value="proposal">Proposal</option>
                    <option value="negotiation">Negotiation</option>
                    <option value="closed">Closed</option>
                </select>
                <x-text-input wire:model="probability" type="number" min="0" max="100" placeholder="Probability %" />
                <select wire:model="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="open">Open</option>
                    <option value="won">Won</option>
                    <option value="lost">Lost</option>
                </select>
                <select wire:model="lead_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Link lead (optional)</option>
                    @foreach($leads as $lead)
                        <option value="{{ $lead['id'] }}">{{ $lead['name'] }}</option>
                    @endforeach
                </select>
                <select wire:model="customer_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Link customer (optional)</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer['id'] }}">{{ $customer['name'] }}</option>
                    @endforeach
                </select>
                <x-text-input wire:model="expected_close_date" type="date" />
                <div class="md:col-span-4">
                    <x-primary-button>Create Deal</x-primary-button>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold">Pipeline</h2>
            <div class="mt-4 space-y-3">
                @forelse($deals as $deal)
                    <div class="border rounded-lg p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <p class="font-medium">{{ $deal['name'] }}</p>
                            <p class="text-sm text-gray-500">{{ ucfirst($deal['stage']) }} | {{ strtoupper($deal['status']) }} | {{ $deal['probability'] }}%</p>
                            <p class="text-sm text-gray-600">${{ number_format((float) $deal['value'], 2) }}</p>
                        </div>
                        @if ($deal['status'] === 'open')
                            <div class="flex gap-2">
                                <button wire:click="markWon({{ $deal['id'] }})" class="text-sm px-3 py-2 rounded border border-green-200 text-green-700 hover:bg-green-50">Mark Won</button>
                                <button wire:click="markLost({{ $deal['id'] }})" class="text-sm px-3 py-2 rounded border border-red-200 text-red-700 hover:bg-red-50">Mark Lost</button>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No deals yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
