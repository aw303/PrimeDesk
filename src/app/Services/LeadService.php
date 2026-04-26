<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Lead;
use App\Services\Concerns\ResolvesTenant;
use Illuminate\Database\Eloquent\Collection;

class LeadService
{
    use ResolvesTenant;

    public function __construct(private WorkflowService $workflowService)
    {
    }

    public function list(): Collection
    {
        return Lead::query()
            ->where('tenant_id', $this->tenantId())
            ->latest()
            ->get();
    }

    public function create(array $data): Lead
    {
        $data['tenant_id'] = $this->tenantId();
        $lead = Lead::query()->create($data);

        $this->workflowService->trigger($lead->tenant_id, 'lead.created', ['lead' => $lead]);

        return $lead;
    }

    public function convertToCustomer(Lead $lead): Customer
    {
        $customer = Customer::query()->create([
            'tenant_id' => $lead->tenant_id,
            'owner_id' => $lead->assigned_to,
            'company_name' => $lead->company_name ?: $lead->title,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'notes' => $lead->notes,
        ]);

        $lead->update([
            'status' => 'converted',
            'customer_id' => $customer->id,
            'converted_at' => now(),
        ]);

        return $customer;
    }
}
