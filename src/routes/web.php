<?php

use App\Livewire\Pages\Admin\AuditLogsPage;
use App\Livewire\Pages\Admin\RolesPage;
use App\Livewire\Pages\Crm\ActivitiesPage;
use App\Livewire\Pages\Crm\CustomersPage;
use App\Livewire\Pages\Crm\DealsPage;
use App\Livewire\Pages\Crm\IntegrationsPage;
use App\Livewire\Pages\Crm\LeadsPage;
use App\Livewire\Pages\Crm\ReportsPage;
use App\Livewire\Pages\Crm\TasksPage;
use App\Livewire\Pages\Crm\WorkflowsPage;
use App\Livewire\Pages\DashboardPage;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardPage::class)->name('dashboard');

    Route::view('profile', 'profile')
        ->name('profile');

    Route::get('customers', CustomersPage::class)
        ->middleware('permission:manage customers')
        ->name('customers.index');

    Route::get('leads', LeadsPage::class)
        ->middleware('permission:manage leads')
        ->name('leads.index');

    Route::get('deals', DealsPage::class)
        ->middleware('permission:manage deals')
        ->name('deals.index');

    Route::get('tasks', TasksPage::class)
        ->middleware('permission:manage tasks')
        ->name('tasks.index');

    Route::get('activities', ActivitiesPage::class)
        ->middleware('permission:manage activities')
        ->name('activities.index');

    Route::get('reports', ReportsPage::class)
        ->middleware('permission:view reports')
        ->name('reports.index');

    Route::get('workflows', WorkflowsPage::class)
        ->middleware('permission:manage workflows')
        ->name('workflows.index');

    Route::get('integrations', IntegrationsPage::class)
        ->middleware('permission:manage integrations')
        ->name('integrations.index');

    Route::get('admin/roles', RolesPage::class)
        ->middleware('role:Admin')
        ->name('admin.roles');

    Route::get('admin/audit-logs', AuditLogsPage::class)
        ->middleware('permission:view audit logs')
        ->name('admin.audit-logs');
});

require __DIR__.'/auth.php';
