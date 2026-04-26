<?php

namespace App\Services\Concerns;

use Illuminate\Support\Facades\Auth;

trait ResolvesTenant
{
    protected function tenantId(): ?int
    {
        return Auth::user()?->tenant_id;
    }
}
