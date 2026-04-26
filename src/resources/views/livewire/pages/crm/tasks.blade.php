<?php

use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] #[Title('Tasks')] class extends Component
{
    public string $title = '';
    public string $description = '';
    public string $priority = 'medium';
    public string $due_at = '';
    public string $assigned_to = '';

    public array $tasks = [];
    public array $users = [];

    public function mount(TaskService $taskService): void
    {
        $this->users = User::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (User $user): array => ['id' => $user->id, 'name' => $user->name])
            ->all();

        $this->reload($taskService);
    }

    public function save(TaskService $taskService): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high'],
            'due_at' => ['nullable', 'date'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $validated['related_type'] = Task::class;
        $validated['related_id'] = 1;

        $taskService->create($validated);

        $this->reset('title', 'description', 'due_at', 'assigned_to');
        $this->priority = 'medium';

        $this->reload($taskService);
    }

    public function complete(int $taskId, TaskService $taskService): void
    {
        $taskService->markComplete(Task::query()->findOrFail($taskId));
        $this->reload($taskService);
    }

    private function reload(TaskService $taskService): void
    {
        $this->tasks = $taskService->list()
            ->map(fn (Task $task): array => [
                'id' => $task->id,
                'title' => $task->title,
                'priority' => $task->priority,
                'status' => $task->status,
                'due_at' => $task->due_at?->toDateTimeString(),
            ])
            ->all();
    }
}; ?>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h1 class="text-xl font-semibold">Tasks & Activities</h1>
            <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-4">
                <x-text-input wire:model="title" placeholder="Task title" />
                <x-text-input wire:model="description" placeholder="Description" />
                <select wire:model="priority" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
                <x-text-input wire:model="due_at" type="datetime-local" />
                <select wire:model="assigned_to" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm md:col-span-2">
                    <option value="">Assign user (optional)</option>
                    @foreach($users as $user)
                        <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                    @endforeach
                </select>
                <div class="md:col-span-4">
                    <x-primary-button>Create Task</x-primary-button>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold">Task List</h2>
            <div class="mt-4 space-y-3">
                @forelse($tasks as $task)
                    <div class="border rounded-lg p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium">{{ $task['title'] }}</p>
                            <p class="text-sm text-gray-500">{{ ucfirst($task['priority']) }} | {{ ucfirst($task['status']) }} | {{ $task['due_at'] ?: 'No due date' }}</p>
                        </div>
                        @if($task['status'] !== 'completed')
                            <button wire:click="complete({{ $task['id'] }})" class="text-sm px-3 py-2 rounded border border-indigo-200 text-indigo-700 hover:bg-indigo-50">Complete</button>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No tasks yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
