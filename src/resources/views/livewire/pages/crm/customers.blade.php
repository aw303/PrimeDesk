
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h1 class="text-xl font-semibold">Customers</h1>
            <p class="text-sm text-gray-500 mt-1">Companies and contacts in your CRM.</p>

            <form wire:submit="saveCustomer" class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-4">
                <x-text-input wire:model="company_name" placeholder="Company name" />
                <x-text-input wire:model="email" placeholder="Company email" />
                <x-text-input wire:model="phone" placeholder="Phone" />
                <select wire:model="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <div class="md:col-span-4">
                    <x-primary-button>Add Customer</x-primary-button>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold">Add Contact</h2>
            <form wire:submit="saveContact" class="grid grid-cols-1 md:grid-cols-5 gap-3 mt-4">
                <select wire:model="contact_customer_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Select customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer['id'] }}">{{ $customer['company_name'] }}</option>
                    @endforeach
                </select>
                <x-text-input wire:model="contact_first_name" placeholder="First name" />
                <x-text-input wire:model="contact_last_name" placeholder="Last name" />
                <x-text-input wire:model="contact_email" placeholder="Email" />
                <x-text-input wire:model="contact_phone" placeholder="Phone" />
                <div class="md:col-span-5">
                    <x-primary-button>Add Contact</x-primary-button>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold">Customer List</h2>
            <div class="mt-4 space-y-3">
                @forelse($customers as $customer)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <p class="font-medium">{{ $customer['company_name'] }}</p>
                            <span class="text-xs px-2 py-1 rounded bg-gray-100">{{ $customer['status'] }}</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ $customer['email'] ?: 'No email' }} | {{ $customer['phone'] ?: 'No phone' }}</p>
                        <div class="mt-2 text-sm text-gray-600">
                            <span class="font-medium">Contacts:</span>
                            @if (count($customer['contacts']) === 0)
                                <span>No contacts yet</span>
                            @else
                                @foreach($customer['contacts'] as $contact)
                                    <span class="inline-block bg-gray-100 rounded px-2 py-1 mr-1 mt-1">{{ $contact['name'] }}{{ $contact['email'] ? ' ('.$contact['email'].')' : '' }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No customers yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
