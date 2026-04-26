<?php

namespace App\Livewire\Pages\Crm;

use App\Models\Contact;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Customers')]
class CustomersPage extends Component
{
    public string $company_name = '';
    public string $email = '';
    public string $phone = '';
    public string $status = 'active';

    public string $contact_customer_id = '';
    public string $contact_first_name = '';
    public string $contact_last_name = '';
    public string $contact_email = '';
    public string $contact_phone = '';

    public array $customers = [];

    public function mount(): void
    {
        $this->reload();
    }

    public function saveCustomer(): void
    {
        $validated = $this->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        app(CustomerService::class)->create($validated);

        $this->reset('company_name', 'email', 'phone');
        $this->reload();
    }

    public function saveContact(): void
    {
        $validated = $this->validate([
            'contact_customer_id' => ['required', 'exists:customers,id'],
            'contact_first_name' => ['required', 'string', 'max:255'],
            'contact_last_name' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
        ]);

        Contact::query()->create([
            'tenant_id' => Auth::user()->tenant_id,
            'customer_id' => (int) $validated['contact_customer_id'],
            'first_name' => $validated['contact_first_name'],
            'last_name' => $validated['contact_last_name'] ?: null,
            'email' => $validated['contact_email'] ?: null,
            'phone' => $validated['contact_phone'] ?: null,
        ]);

        $this->reset('contact_customer_id', 'contact_first_name', 'contact_last_name', 'contact_email', 'contact_phone');
        $this->reload();
    }

    public function render()
    {
        return view('livewire.pages.crm.customers');
    }

    private function reload(): void
    {
        $this->customers = app(CustomerService::class)
            ->list()
            ->load('contacts:id,customer_id,first_name,last_name,email')
            ->map(fn (Customer $customer): array => [
                'id' => $customer->id,
                'company_name' => $customer->company_name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'status' => $customer->status,
                'contacts' => $customer->contacts->map(fn (Contact $contact): array => [
                    'name' => trim($contact->first_name.' '.$contact->last_name),
                    'email' => $contact->email,
                ])->all(),
            ])
            ->all();
    }
}
