# PrimeDesk

PrimeDesk CRM development workspace using Docker + Laravel (Blade, Livewire, Alpine).

## Implemented Modules

- Auth (Breeze + Livewire)
- Multi-tenant foundation (Tenant model + tenant-scoped records)
- User & Role Management (RBAC via Spatie)
- Customers & Contacts
- Leads (including lead-to-customer conversion)
- Deals / Opportunities (pipeline + won/lost handling)
- Tasks & Activities
- Dashboard & KPI snapshots
- Reports (lead/deal breakdown)
- Workflow Automation (trigger/action rules)
- Integrations registry (SMTP/Mailgun/WhatsApp/External API config records)
- Audit Logging (create/update/delete tracking)

## Docker Stack

- PHP 8.3 (FPM)
- Nginx
- MySQL 8.4
- Redis 7

## Local Setup

```bash
cp .env.example .env
```

Start Docker Desktop/daemon, then:

```bash
docker compose up -d --build
```

Install dependencies and initialize app in containers:

```bash
docker compose exec app composer install
docker compose exec app npm install
docker compose exec app npm run build
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

App URL: `http://localhost:8080`

## Demo Users (seeded)

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
