<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\IntegrationConnection;
use App\Models\Lead;
use App\Models\Task;
use App\Models\Tenant;
use App\Models\User;
use App\Models\WorkflowAutomation;
use Illuminate\Database\Seeder;

class CrmDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = Tenant::query()->where('slug', 'primedesk-demo')->first();

        if (! $tenant) {
            return;
        }

        $manager = User::query()->where('tenant_id', $tenant->id)->where('email', 'manager@primedesk.local')->first();
        $agent = User::query()->where('tenant_id', $tenant->id)->where('email', 'agent@primedesk.local')->first();

        $customer = Customer::query()->firstOrCreate(
            ['tenant_id' => $tenant->id, 'company_name' => 'Acme Corporation'],
            [
                'owner_id' => $manager?->id,
                'email' => 'hello@acme.test',
                'phone' => '+1-555-0100',
                'industry' => 'Technology',
                'status' => 'active',
            ]
        );

        Contact::query()->firstOrCreate(
            ['tenant_id' => $tenant->id, 'customer_id' => $customer->id, 'email' => 'sarah@acme.test'],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'phone' => '+1-555-0101',
                'job_title' => 'Operations Director',
                'is_primary' => true,
            ]
        );

        $lead = Lead::query()->firstOrCreate(
            ['tenant_id' => $tenant->id, 'title' => 'Inbound Website Lead - Bright Labs'],
            [
                'assigned_to' => $agent?->id,
                'company_name' => 'Bright Labs',
                'contact_name' => 'John Doe',
                'email' => 'john@brightlabs.test',
                'phone' => '+1-555-0190',
                'status' => 'qualified',
                'priority' => 'high',
                'estimated_value' => 25000,
            ]
        );

        $deal = Deal::query()->firstOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'Bright Labs Annual Subscription'],
            [
                'lead_id' => $lead->id,
                'owner_id' => $agent?->id,
                'stage' => 'proposal',
                'status' => 'open',
                'value' => 24000,
                'probability' => 60,
                'expected_close_date' => now()->addDays(21)->toDateString(),
            ]
        );

        Task::query()->firstOrCreate(
            ['tenant_id' => $tenant->id, 'title' => 'Follow up with Bright Labs procurement'],
            [
                'assigned_to' => $agent?->id,
                'related_type' => Deal::class,
                'related_id' => $deal->id,
                'priority' => 'high',
                'status' => 'pending',
                'due_at' => now()->addDays(2),
            ]
        );

        Activity::query()->firstOrCreate(
            ['tenant_id' => $tenant->id, 'subject' => 'Discovery call with Bright Labs'],
            [
                'created_by' => $agent?->id,
                'related_type' => Lead::class,
                'related_id' => $lead->id,
                'activity_type' => 'call',
                'scheduled_at' => now()->subDay(),
                'completed_at' => now()->subDay()->addHour(),
            ]
        );

        WorkflowAutomation::query()->firstOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'Auto-assign new leads to agent'],
            [
                'trigger_event' => 'lead.created',
                'action_type' => 'assign_agent',
                'payload' => ['user_id' => $agent?->id],
                'is_active' => true,
            ]
        );

        IntegrationConnection::query()->firstOrCreate(
            ['tenant_id' => $tenant->id, 'provider' => 'whatsapp', 'name' => 'superchat-default'],
            [
                'credentials' => ['api_key' => 'demo-key', 'api_secret' => 'demo-secret'],
                'is_active' => true,
            ]
        );
    }
}
