<?php

namespace App\Livewire\Pages\Crm;

use App\Models\Customer;
use App\Models\Deal;
use App\Models\Lead;
use App\Services\DealService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Deals')]
class DealsPage extends Component
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

    public function mount(): void
    {
        $tenantId = auth()->user()->tenant_id;

        $this->leads = Lead::query()->where('tenant_id', $tenantId)->orderBy('title')->get(['id', 'title'])
            ->map(fn (Lead $lead): array => ['id' => $lead->id, 'name' => $lead->title])->all();

        $this->customers = Customer::query()->where('tenant_id', $tenantId)->orderBy('company_name')->get(['id', 'company_name'])
            ->map(fn (Customer $customer): array => ['id' => $customer->id, 'name' => $customer->company_name])->all();

        $this->reload();
    }

    public function save(): void
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

        app(DealService::class)->create($validated);

        $this->reset('name', 'value', 'lead_id', 'customer_id', 'expected_close_date');
        $this->stage = 'prospecting';
        $this->probability = '10';
        $this->status = 'open';

        $this->reload();
    }

    public function markWon(int $dealId): void
    {
        app(DealService::class)->updateStage(Deal::query()->findOrFail($dealId), 'closed', 'won');
        $this->reload();
    }

    public function markLost(int $dealId): void
    {
        app(DealService::class)->updateStage(Deal::query()->findOrFail($dealId), 'closed', 'lost');
        $this->reload();
    }

    public function render()
    {
        return view('livewire.pages.crm.deals');
    }

    private function reload(): void
    {
        $this->deals = app(DealService::class)
            ->list()
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
}
