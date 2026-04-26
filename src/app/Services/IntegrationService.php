<?php

namespace App\Services;

use App\Models\IntegrationConnection;
use App\Services\Concerns\ResolvesTenant;
use Illuminate\Database\Eloquent\Collection;

class IntegrationService
{
    use ResolvesTenant;

    public function list(): Collection
    {
        return IntegrationConnection::query()
            ->where('tenant_id', $this->tenantId())
            ->orderBy('provider')
            ->get();
    }

    public function upsert(array $data): IntegrationConnection
    {
        return IntegrationConnection::query()->updateOrCreate(
            [
                'tenant_id' => $this->tenantId(),
                'provider' => $data['provider'],
                'name' => $data['name'],
            ],
            [
                'credentials' => $data['credentials'] ?? [],
                'is_active' => (bool) ($data['is_active'] ?? true),
            ]
        );
    }
}
