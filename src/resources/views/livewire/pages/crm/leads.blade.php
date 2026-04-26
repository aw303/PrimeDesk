
<div
    class="py-6"
    x-data="{
        showCreateForm: true,
        search: '',
        statusFilter: 'all',
        matches(text, status) {
            const queryOk = !this.search || text.toLowerCase().includes(this.search.toLowerCase());
            const statusOk = this.statusFilter === 'all' || status === this.statusFilter;
            return queryOk && statusOk;
        }
    }"
>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between gap-3">
                <h1 class="text-xl font-semibold">Leads</h1>
                <button type="button" @click="showCreateForm = !showCreateForm" class="text-sm px-3 py-2 rounded border border-indigo-200 text-indigo-700 hover:bg-indigo-50">
                    Toggle Lead Form
                </button>
            </div>
            <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-4" x-show="showCreateForm" x-transition>
                <x-text-input wire:model="title" placeholder="Lead title" />
                <x-text-input wire:model="company_name" placeholder="Company" />
                <x-text-input wire:model="contact_name" placeholder="Contact person" />
                <x-text-input wire:model="email" placeholder="Email" />
                <x-text-input wire:model="phone" placeholder="Phone" />
                <select wire:model="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="new">New</option>
                    <option value="qualified">Qualified</option>
                    <option value="proposal">Proposal</option>
                    <option value="won">Won</option>
                    <option value="lost">Lost</option>
                </select>
                <select wire:model="priority" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
                <x-text-input wire:model="estimated_value" placeholder="Estimated value" type="number" step="0.01" min="0" />
                <select wire:model="assigned_to" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm md:col-span-2">
                    <option value="">Assign agent (optional)</option>
                    @foreach($users as $user)
                        <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                    @endforeach
                </select>
                <div class="md:col-span-4">
                    <x-primary-button>Create Lead</x-primary-button>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <h2 class="text-lg font-semibold">Lead List</h2>
                <div class="flex gap-2">
                    <input x-model="search" type="text" placeholder="Search leads..." class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" />
                    <select x-model="statusFilter" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                        <option value="all">All statuses</option>
                        <option value="new">New</option>
                        <option value="qualified">Qualified</option>
                        <option value="proposal">Proposal</option>
                        <option value="won">Won</option>
                        <option value="lost">Lost</option>
                        <option value="converted">Converted</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 space-y-3">
                @forelse($leads as $lead)
                    <div
                        class="border border-gray-200 rounded-lg p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3"
                        x-show="matches(@js(strtolower(($lead['title'] ?? '').' '.($lead['company_name'] ?? ''))), @js($lead['status']))"
                        x-transition
                    >
                        <div>
                            <p class="font-medium">{{ $lead['title'] }}</p>
                            <p class="text-sm text-gray-500">{{ $lead['company_name'] ?: 'No company' }} | {{ ucfirst($lead['status']) }} | {{ ucfirst($lead['priority']) }}</p>
                            <p class="text-sm text-gray-600 mt-1">Value: ${{ number_format((float) $lead['estimated_value'], 2) }}</p>
                        </div>

                        @if (!$lead['converted_at'] && $lead['status'] !== 'converted')
                            <button class="text-sm px-3 py-2 rounded border border-indigo-200 text-indigo-700 hover:bg-indigo-50" wire:click="convert({{ $lead['id'] }})">
                                Convert to Customer
                            </button>
                        @else
                            <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-800">Converted</span>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No leads yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
