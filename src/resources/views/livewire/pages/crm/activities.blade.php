
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
