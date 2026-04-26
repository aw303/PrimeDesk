<?php

namespace App\Services;

use App\Models\Activity;
use App\Services\Concerns\ResolvesTenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ActivityService
{
    use ResolvesTenant;

    public function list(): Collection
    {
        return Activity::query()
            ->where('tenant_id', $this->tenantId())
            ->latest()
            ->get();
    }

    public function create(array $data): Activity
    {
        $data['tenant_id'] = $this->tenantId();
        $data['created_by'] = $data['created_by'] ?? Auth::id();

        return Activity::query()->create($data);
    }
}
