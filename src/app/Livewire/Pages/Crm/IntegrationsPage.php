<?php

namespace App\Livewire\Pages\Crm;

use App\Services\IntegrationService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Integrations')]
class IntegrationsPage extends Component
{
    public string $provider = 'smtp';
    public string $name = 'default';
    public string $api_key = '';
    public string $api_secret = '';
    public bool $is_active = true;

    public array $connections = [];

    public function mount(): void
    {
        $this->reload();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'provider' => ['required', 'in:smtp,mailgun,whatsapp,external_api'],
            'name' => ['required', 'string', 'max:255'],
            'api_key' => ['nullable', 'string'],
            'api_secret' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        app(IntegrationService::class)->upsert([
            'provider' => $validated['provider'],
            'name' => $validated['name'],
            'credentials' => [
                'api_key' => $validated['api_key'] ?? null,
                'api_secret' => $validated['api_secret'] ?? null,
            ],
            'is_active' => $validated['is_active'],
        ]);

        $this->reset('api_key', 'api_secret');
        $this->reload();
    }

    public function render()
    {
        return view('livewire.pages.crm.integrations');
    }

    private function reload(): void
    {
        $this->connections = app(IntegrationService::class)
            ->list()
            ->map(fn ($connection): array => [
                'provider' => $connection->provider,
                'name' => $connection->name,
                'is_active' => $connection->is_active,
                'updated_at' => $connection->updated_at?->toDateTimeString(),
            ])
            ->all();
    }
}
