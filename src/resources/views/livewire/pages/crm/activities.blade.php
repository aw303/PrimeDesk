<?php

use App\Models\Activity;
use App\Services\ActivityService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] #[Title('Activities')] class extends Component
{
    public string $activity_type = 'meeting';
    public string $subject = '';
    public string $details = '';
    public string $scheduled_at = '';

    public array $activities = [];

    public function mount(ActivityService $activityService): void
    {
        $this->reload($activityService);
    }

    public function save(ActivityService $activityService): void
    {
        $validated = $this->validate([
            'activity_type' => ['required', 'in:meeting,call,follow-up,note'],
            'subject' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        $validated['related_type'] = Activity::class;
        $validated['related_id'] = 1;

        $activityService->create($validated);

        $this->reset('subject', 'details', 'scheduled_at');
        $this->activity_type = 'meeting';

        $this->reload($activityService);
    }

    private function reload(ActivityService $activityService): void
    {
        $this->activities = $activityService->list()
            ->map(fn (Activity $activity): array => [
                'id' => $activity->id,
                'activity_type' => $activity->activity_type,
                'subject' => $activity->subject,
                'scheduled_at' => $activity->scheduled_at?->toDateTimeString(),
            ])
            ->all();
    }
}; ?>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h1 class="text-xl font-semibold">Activities</h1>
            <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-4">
                <select wire:model="activity_type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="meeting">Meeting</option>
                    <option value="call">Call</option>
                    <option value="follow-up">Follow Up</option>
                    <option value="note">Note</option>
                </select>
                <x-text-input wire:model="subject" placeholder="Subject" />
                <x-text-input wire:model="scheduled_at" type="datetime-local" />
                <x-text-input wire:model="details" placeholder="Details" />
                <div class="md:col-span-4">
                    <x-primary-button>Add Activity</x-primary-button>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold">Activity Timeline</h2>
            <div class="mt-4 space-y-3">
                @forelse($activities as $activity)
                    <div class="border rounded-lg p-4">
                        <p class="font-medium">{{ $activity['subject'] }}</p>
                        <p class="text-sm text-gray-500">{{ ucfirst($activity['activity_type']) }} | {{ $activity['scheduled_at'] ?: 'No schedule' }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No activities yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
