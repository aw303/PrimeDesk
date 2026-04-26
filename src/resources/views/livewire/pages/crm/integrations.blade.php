
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h1 class="text-xl font-semibold">Integrations</h1>
            <p class="text-sm text-gray-500 mt-1">Configure SMTP, Mailgun, WhatsApp, and external APIs.</p>
            <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-4">
                <select wire:model="provider" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="smtp">SMTP</option>
                    <option value="mailgun">Mailgun</option>
                    <option value="whatsapp">WhatsApp</option>
                    <option value="external_api">External API</option>
                </select>
                <x-text-input wire:model="name" placeholder="Connection name" />
                <x-text-input wire:model="api_key" placeholder="API Key" />
                <x-text-input wire:model="api_secret" placeholder="API Secret" />
                <label class="inline-flex items-center gap-2 md:col-span-2">
                    <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                    <span class="text-sm text-gray-700">Active</span>
                </label>
                <div class="md:col-span-4">
                    <x-primary-button>Save Integration</x-primary-button>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold">Configured Connections</h2>
            <div class="mt-4 space-y-3">
                @forelse($connections as $connection)
                    <div class="border rounded-lg p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium">{{ strtoupper($connection['provider']) }} - {{ $connection['name'] }}</p>
                            <p class="text-sm text-gray-500">Updated: {{ $connection['updated_at'] ?: 'N/A' }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded {{ $connection['is_active'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $connection['is_active'] ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No integrations configured yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
