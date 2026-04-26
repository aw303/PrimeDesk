<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Task;
use App\Services\Concerns\ResolvesTenant;

class DashboardService
{
    use ResolvesTenant;

    public function metrics(): array
    {
        $tenantId = $this->tenantId();

        $customers = Customer::query()->where('tenant_id', $tenantId)->count();
        $leads = Lead::query()->where('tenant_id', $tenantId)->count();
        $convertedLeads = Lead::query()->where('tenant_id', $tenantId)->whereNotNull('converted_at')->count();
        $openDeals = Deal::query()->where('tenant_id', $tenantId)->where('status', 'open')->count();
        $wonRevenue = Deal::query()->where('tenant_id', $tenantId)->where('status', 'won')->sum('value');
        $pendingTasks = Task::query()->where('tenant_id', $tenantId)->where('status', 'pending')->count();

        return [
            'customers' => $customers,
            'leads' => $leads,
            'conversion_rate' => $leads > 0 ? round(($convertedLeads / $leads) * 100, 2) : 0,
            'open_deals' => $openDeals,
            'won_revenue' => (float) $wonRevenue,
            'pending_tasks' => $pendingTasks,
        ];
    }
}
