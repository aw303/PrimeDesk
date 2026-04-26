<?php

namespace App\Livewire\Pages\Admin;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
#[Title('Role Management')]
class RolesPage extends Component
{
    public string $name = '';

    public array $selectedPermissions = [];
    public array $roles = [];
    public array $permissions = [];

    public function mount(): void
    {
        $this->refreshData();
    }

    public function createRole(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:50', 'unique:roles,name'],
            'selectedPermissions' => ['array'],
            'selectedPermissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role = Role::create(['name' => $validated['name']]);
        $role->syncPermissions($validated['selectedPermissions'] ?? []);

        $this->reset('name', 'selectedPermissions');
        $this->refreshData();

        $this->dispatch('role-saved');
    }

    public function deleteRole(int $roleId): void
    {
        $role = Role::query()->findOrFail($roleId);

        if ($role->name === 'Admin') {
            $this->addError('name', 'The Admin role cannot be deleted.');
            return;
        }

        $role->delete();
        $this->refreshData();
    }

    public function render()
    {
        return view('livewire.pages.admin.roles');
    }

    private function refreshData(): void
    {
        $this->permissions = Permission::query()
            ->orderBy('name')
            ->pluck('name')
            ->all();

        $this->roles = Role::query()
            ->with('permissions:id,name')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (Role $role): array => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name')->sort()->values()->all(),
            ])
            ->all();
    }
}
