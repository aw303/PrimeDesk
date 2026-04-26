# PrimeDesk

PrimeDesk CRM development workspace using Docker + Laravel (Blade, Livewire, Alpine).

## What is already scaffolded

- Laravel 13 app in `src/`
- Breeze auth with Livewire/Volt
- RBAC with `spatie/laravel-permission`
- Admin role management page at `/admin/roles`
- Seeded default roles: `Admin`, `Manager`, `Agent`
- Seeded default admin user:
  - Email: `admin@primedesk.local`
  - Password: `password`

## Stack

- PHP 8.3 (FPM)
- Nginx
- MySQL 8.4
- Redis 7
- Composer 2

## 1) Prepare environment

```bash
cp .env.example .env
```

If your local user id is not `1000`, update `UID` and `GID` in `.env`:

```bash
id -u
id -g
```

## 2) Start Docker (required)

Start Docker Desktop (or Docker daemon), then run:

```bash
docker compose up -d --build
```

App URL: `http://localhost:8080`

## 3) Install app dependencies in container

```bash
docker compose exec app composer install
docker compose exec app npm install
docker compose exec app npm run build
```

## 4) Configure Laravel environment

The project is already prepared for Docker in `src/.env`:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=primedesk
DB_USERNAME=primedesk
DB_PASSWORD=secret

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## 5) Migrate and seed

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

## Useful commands

```bash
# Start / stop
make up
make down

# Logs
make logs

# App shell
make shell

# Artisan / Composer / NPM shortcuts
make artisan cmd="route:list"
make composer cmd="update"
make npm cmd="run dev"

# Migrations and tests
make migrate-seed
make test
```

## App notes

- Laravel source code lives in `src/`.
- Role management UI is available only for `Admin` users.
- MySQL data is persisted in the `mysql_data` Docker volume.
- Redis data is persisted in the `redis_data` Docker volume.
