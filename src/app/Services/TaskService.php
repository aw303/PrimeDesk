<?php

namespace App\Services;

use App\Models\Task;
use App\Services\Concerns\ResolvesTenant;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    use ResolvesTenant;

    public function list(): Collection
    {
        return Task::query()
            ->where('tenant_id', $this->tenantId())
            ->latest()
            ->get();
    }

    public function create(array $data): Task
    {
        $data['tenant_id'] = $this->tenantId();

        return Task::query()->create($data);
    }

    public function markComplete(Task $task): Task
    {
        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return $task;
    }
}
