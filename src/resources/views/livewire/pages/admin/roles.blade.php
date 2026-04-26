
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-xl font-semibold">Role Management</h1>
                <p class="text-sm text-gray-500 mt-1">Create and manage CRM access roles.</p>

                <form wire:submit="createRole" class="mt-6 space-y-4">
                    <div>
                        <x-input-label for="role-name" :value="__('Role Name')" />
                        <x-text-input id="role-name" wire:model="name" type="text" class="mt-1 block w-full" placeholder="e.g. Sales Manager" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label :value="__('Permissions')" />
                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                            @forelse($permissions as $permission)
                                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                    <input wire:model="selectedPermissions" value="{{ $permission }}" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                    <span>{{ $permission }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500">No permissions found.</p>
                            @endforelse
                        </div>
                        <x-input-error :messages="$errors->get('selectedPermissions.*')" class="mt-2" />
                    </div>

                    <x-primary-button>
                        {{ __('Create Role') }}
                    </x-primary-button>
                </form>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-lg font-semibold">Existing Roles</h2>

                <div class="mt-4 divide-y divide-gray-200">
                    @forelse($roles as $role)
                        <div class="py-4 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <p class="font-medium">{{ $role['name'] }}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ count($role['permissions']) }} permission(s)
                                </p>
                                <div class="mt-2 flex flex-wrap gap-1">
                                    @forelse($role['permissions'] as $permission)
                                        <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-700">{{ $permission }}</span>
                                    @empty
                                        <span class="text-xs text-gray-500">No permissions assigned.</span>
                                    @endforelse
                                </div>
                            </div>

                            @if ($role['name'] !== 'Admin')
                                <button
                                    type="button"
                                    wire:click="deleteRole({{ $role['id'] }})"
                                    wire:confirm="Are you sure you want to delete this role?"
                                    class="inline-flex items-center rounded-md border border-red-200 px-3 py-2 text-sm text-red-600 hover:bg-red-50"
                                >
                                    Delete
                                </button>
                            @endif
                        </div>
                    @empty
                        <p class="py-4 text-sm text-gray-500">No roles created yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
