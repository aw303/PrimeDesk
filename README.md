# PrimeDesk

PrimeDesk CRM built with the **TALL stack**:
- **T**ailwind CSS
- **A**lpine.js
- **L**aravel
- **L**ivewire (class-based components)

This repository includes Docker-based local development and an end-to-end CRM MVP.

## Features Implemented

- Authentication (Laravel Breeze + Livewire)
- Multi-tenant foundation (`tenants` + tenant-scoped data)
- User roles and permissions (Spatie RBAC)
- Customers and Contacts
- Leads management (including lead conversion to customer)
- Deals / Opportunities pipeline
- Tasks and Activities
- Dashboard KPI cards
- Reports (lead status + deal stage breakdown)
- Workflow automation (trigger/action records)
- Integrations registry (SMTP, Mailgun, WhatsApp, External API)
- Audit logging for create/update/delete events

## Frontend (TALL)

- Tailwind is used for all UI styling.
- Alpine.js is actively used in CRM screens for:
  - form toggles
  - client-side filtering/search
  - lightweight transitions
- Livewire pages are implemented with **class-based components** under:
  - `src/app/Livewire/Pages`

## Docker Stack

- PHP 8.3 (FPM)
- Nginx
- MySQL 8.4
- Redis 7

## Local Setup

```bash
cp .env.example .env
docker compose up -d --build
```

Install dependencies and initialize:

```bash
docker compose exec app composer install
docker compose exec app npm install
docker compose exec app npm run build
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

App URL:
- `http://localhost:8080`

## Seeded Users

- `admin@primedesk.local` / `password`
- `manager@primedesk.local` / `password`
- `agent@primedesk.local` / `password`

## Main Routes

- `/dashboard`
- `/customers`
- `/leads`
- `/deals`
- `/tasks`
- `/activities`
- `/reports`
- `/workflows`
- `/integrations`
- `/admin/roles`
- `/admin/audit-logs`

## Useful Commands

```bash
make up
make down
make logs
make shell
make migrate-seed
make test
```
