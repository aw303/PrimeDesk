<?php

namespace App\Livewire\Pages\Crm;

use App\Models\Activity;
use App\Services\ActivityService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Activities')]
class ActivitiesPage extends Component
{
    public string $activity_type = 'meeting';
    public string $subject = '';
    public string $details = '';
    public string $scheduled_at = '';

    public array $activities = [];

    public function mount(): void
    {
        $this->reload();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'activity_type' => ['required', 'in:meeting,call,follow-up,note'],
            'subject' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        app(ActivityService::class)->create($validated);

        $this->reset('subject', 'details', 'scheduled_at');
        $this->activity_type = 'meeting';

        $this->reload();
    }

    public function render()
    {
        return view('livewire.pages.crm.activities');
    }

    private function reload(): void
    {
        $this->activities = app(ActivityService::class)
            ->list()
            ->map(fn (Activity $activity): array => [
                'id' => $activity->id,
                'activity_type' => $activity->activity_type,
                'subject' => $activity->subject,
                'scheduled_at' => $activity->scheduled_at?->toDateTimeString(),
            ])
            ->all();
    }
}
