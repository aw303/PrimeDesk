<?php

namespace App\Services;

use App\Models\Customer;
use App\Services\Concerns\ResolvesTenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class CustomerService
{
    use ResolvesTenant;

    public function list(): Collection
    {
        return Customer::query()
            ->where('tenant_id', $this->tenantId())
            ->latest()
            ->get();
    }

    public function create(array $data): Customer
    {
        $data['tenant_id'] = $this->tenantId();
        $data['owner_id'] = $data['owner_id'] ?? Auth::id();

        return Customer::query()->create($data);
    }
}
