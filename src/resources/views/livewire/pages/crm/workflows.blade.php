
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h1 class="text-xl font-semibold">Workflow Automation</h1>
            <p class="text-sm text-gray-500 mt-1">Configure simple trigger-based actions.</p>

            <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-4">
                <x-text-input wire:model="name" placeholder="Automation name" />
                <select wire:model="trigger_event" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="lead.created">Lead Created</option>
                    <option value="deal.closed">Deal Closed</option>
                </select>
                <select wire:model="action_type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="assign_agent">Assign Agent</option>
                    <option value="mark_high_priority">Mark High Priority</option>
                </select>
                <x-text-input wire:model="payload_user_id" type="number" min="1" placeholder="User ID (for assign)" />
                <div class="md:col-span-4">
                    <x-primary-button>Create Workflow</x-primary-button>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold">Automations</h2>
            <div class="mt-4 space-y-3">
                @forelse($workflows as $workflow)
                    <div class="border rounded-lg p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium">{{ $workflow['name'] }}</p>
                            <p class="text-sm text-gray-500">{{ $workflow['trigger_event'] }} -> {{ $workflow['action_type'] }}</p>
                        </div>
                        <button wire:click="toggle({{ $workflow['id'] }})" class="text-sm px-3 py-2 rounded border {{ $workflow['is_active'] ? 'border-green-200 text-green-700' : 'border-gray-200 text-gray-600' }}">
                            {{ $workflow['is_active'] ? 'Active' : 'Inactive' }}
                        </button>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No workflows configured yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
