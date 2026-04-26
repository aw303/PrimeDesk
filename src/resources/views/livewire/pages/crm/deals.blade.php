
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h1 class="text-xl font-semibold">Deals / Opportunities</h1>
            <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-4">
                <x-text-input wire:model="name" placeholder="Deal name" />
                <x-text-input wire:model="value" type="number" step="0.01" min="0" placeholder="Value" />
                <select wire:model="stage" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="prospecting">Prospecting</option>
                    <option value="qualified">Qualified</option>
                    <option value="proposal">Proposal</option>
                    <option value="negotiation">Negotiation</option>
                    <option value="closed">Closed</option>
                </select>
                <x-text-input wire:model="probability" type="number" min="0" max="100" placeholder="Probability %" />
                <select wire:model="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="open">Open</option>
                    <option value="won">Won</option>
                    <option value="lost">Lost</option>
                </select>
                <select wire:model="lead_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Link lead (optional)</option>
                    @foreach($leads as $lead)
                        <option value="{{ $lead['id'] }}">{{ $lead['name'] }}</option>
                    @endforeach
                </select>
                <select wire:model="customer_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Link customer (optional)</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer['id'] }}">{{ $customer['name'] }}</option>
                    @endforeach
                </select>
                <x-text-input wire:model="expected_close_date" type="date" />
                <div class="md:col-span-4">
                    <x-primary-button>Create Deal</x-primary-button>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold">Pipeline</h2>
            <div class="mt-4 space-y-3">
                @forelse($deals as $deal)
                    <div class="border rounded-lg p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <p class="font-medium">{{ $deal['name'] }}</p>
                            <p class="text-sm text-gray-500">{{ ucfirst($deal['stage']) }} | {{ strtoupper($deal['status']) }} | {{ $deal['probability'] }}%</p>
                            <p class="text-sm text-gray-600">${{ number_format((float) $deal['value'], 2) }}</p>
                        </div>
                        @if ($deal['status'] === 'open')
                            <div class="flex gap-2">
                                <button wire:click="markWon({{ $deal['id'] }})" class="text-sm px-3 py-2 rounded border border-green-200 text-green-700 hover:bg-green-50">Mark Won</button>
                                <button wire:click="markLost({{ $deal['id'] }})" class="text-sm px-3 py-2 rounded border border-red-200 text-red-700 hover:bg-red-50">Mark Lost</button>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No deals yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
