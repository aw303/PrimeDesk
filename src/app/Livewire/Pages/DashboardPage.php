<?php

namespace App\Livewire\Pages;

use App\Services\DashboardService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class DashboardPage extends Component
{
    public array $metrics = [];

    public function mount(): void
    {
        $this->metrics = app(DashboardService::class)->metrics();
    }

    public function render()
    {
        return view('livewire.pages.dashboard');
    }
}
