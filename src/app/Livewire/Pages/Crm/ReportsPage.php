<?php

namespace App\Livewire\Pages\Crm;

use App\Models\Deal;
use App\Models\Lead;
use App\Services\DashboardService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Reports')]
class ReportsPage extends Component
{
    public array $metrics = [];
    public array $leadStatus = [];
    public array $dealStage = [];

    public function mount(): void
    {
        $tenantId = auth()->user()->tenant_id;

        $this->metrics = app(DashboardService::class)->metrics();

        $this->leadStatus = Lead::query()
            ->where('tenant_id', $tenantId)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $this->dealStage = Deal::query()
            ->where('tenant_id', $tenantId)
            ->selectRaw('stage, count(*) as total')
            ->groupBy('stage')
            ->pluck('total', 'stage')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.pages.crm.reports');
    }
}
