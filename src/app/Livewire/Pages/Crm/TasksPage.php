<?php

namespace App\Livewire\Pages\Crm;

use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Tasks')]
class TasksPage extends Component
{
    public string $title = '';
    public string $description = '';
    public string $priority = 'medium';
    public string $due_at = '';
    public string $assigned_to = '';

    public array $tasks = [];
    public array $users = [];

    public function mount(): void
    {
        $this->users = User::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (User $user): array => ['id' => $user->id, 'name' => $user->name])
            ->all();

        $this->reload();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high'],
            'due_at' => ['nullable', 'date'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        app(TaskService::class)->create($validated);

        $this->reset('title', 'description', 'due_at', 'assigned_to');
        $this->priority = 'medium';

        $this->reload();
    }

    public function complete(int $taskId): void
    {
        app(TaskService::class)->markComplete(Task::query()->findOrFail($taskId));
        $this->reload();
    }

    public function render()
    {
        return view('livewire.pages.crm.tasks');
    }

    private function reload(): void
    {
        $this->tasks = app(TaskService::class)
            ->list()
            ->map(fn (Task $task): array => [
                'id' => $task->id,
                'title' => $task->title,
                'priority' => $task->priority,
                'status' => $task->status,
                'due_at' => $task->due_at?->toDateTimeString(),
            ])
            ->all();
    }
}
