<?php

namespace App\Services;

use App\Models\Deal;
use App\Services\Concerns\ResolvesTenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class DealService
{
    use ResolvesTenant;

    public function __construct(private WorkflowService $workflowService)
    {
    }

    public function list(): Collection
    {
        return Deal::query()
            ->where('tenant_id', $this->tenantId())
            ->latest()
            ->get();
    }

    public function create(array $data): Deal
    {
        $data['tenant_id'] = $this->tenantId();
        $data['owner_id'] = $data['owner_id'] ?? Auth::id();

        return Deal::query()->create($data);
    }

    public function updateStage(Deal $deal, string $stage, ?string $status = null): Deal
    {
        $deal->stage = $stage;
        $deal->status = $status ?? $deal->status;

        if ($deal->status === 'won' || $deal->status === 'lost') {
            $deal->closed_at = now();

            $this->workflowService->trigger($deal->tenant_id, 'deal.closed', ['deal' => $deal]);
        }

        $deal->save();

        return $deal;
    }
}
