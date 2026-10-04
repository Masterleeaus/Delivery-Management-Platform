![Delivery Management Reference Application — ATTRIBUTED SOURCE REFERENCE](docs/images/portfolio-banner.svg)

# Delivery Management Platform

> A modular multi-tenant Laravel application for delivery-company administration, customer accounts, orders, agents, and fleet operations.

Delivery Management Platform provides a reference architecture for delivery businesses that need tenant-aware operations rather than a single undifferentiated admin panel. Its modular monolith separates delivery domains, role-specific surfaces, authentication, and fleet concerns so teams can study how the pieces fit together in one Laravel application.

## What the platform demonstrates

| Capability | Implementation evidence |
|---|---|
| Modular delivery domains | `modules_statuses.json`, `config/modules.php`, and `modules/*/module.json` define enabled Agent, Client, Manager, Order, Tenant, Truck, User, and supporting modules. |
| Tenant-aware operations | `modules/Tenant/`, `modules/Order/`, `modules/Agent/`, and `modules/Truck/` contain domain entities, migrations, repositories, services, and Filament resources. |
| Role-specific workflows | `modules/*/Filament/Agent/` and `modules/*/Filament/Manager/` provide separate operational and administration surfaces. |
| API and infrastructure | `modules/User/Routes/API/V1/`, PHPUnit tooling, and Laravel Sail/Docker assets provide a concrete API/module/runtime reference. |

The value of this repository is architectural: it makes tenant boundaries, order operations, fleet concerns, permissions, and module composition inspectable in a single full-stack codebase.

**Provenance:** the source retains its Mohaphez / `mohaphez/delivery-platform` lineage, which remains documented below; the repository is presented as an attributed reference application rather than an unqualified original-work claim.

## Verified Capabilities

- Laravel 10 application architecture.
- Modular domains for Agent, Client, Core, Manager, Order, Permission, Support, Tenant, Theme, Truck and User concerns.
- Authentication middleware and Laravel Sanctum dependency.
- Multi-tenant application structure.
- Order-management domain module.
- Agent/client/manager application separation.
- Fleet/truck domain module.
- Role and permission infrastructure.
- Theme/module composition.
- PHPUnit test tooling.
- Docker/Sail-oriented development setup.
- Composer module merging through `nwidart/laravel-modules` and the repository's module manifests.

## Architecture

```mermaid
flowchart TD
    U[Users] --> APP[Laravel Application]
    APP --> AUTH[Authentication + Permissions]
    AUTH --> TEN[Tenant Boundary]
    TEN --> CLIENT[Client Module]
    TEN --> AGENT[Agent Module]
    TEN --> ORDER[Order Module]
    TEN --> TRUCK[Truck / Fleet Module]
    TEN --> SUPPORT[Support Module]
    APP --> ADMIN[Manager / Filament Modules]
    ORDER --> DB[(Tenant Data)]
    AGENT --> DB
    CLIENT --> DB
    TRUCK --> DB
```

## Example Workflow

1. A user authenticates into the appropriate tenant/application surface.
2. Tenant context determines the isolated company scope.
3. A client or agent creates/manages an order through the order domain.
4. Role/permission rules control access to operational actions.
5. Fleet and user modules provide related operational entities.
6. Manager/admin surfaces expose system and tenant administration.

## Tech Stack

| Area | Technology |
|---|---|
| Language | PHP 8.2, JavaScript |
| Backend | Laravel 10 |
| Frontend | Laravel/Vite; modular theme assets |
| Authentication | Laravel Sanctum |
| Architecture | `nwidart/laravel-modules` |
| Data | Laravel database layer |
| Testing | PHPUnit 10 |
| Infrastructure | Laravel Sail/Docker |

## Code map and evidence

| Concern | Location | What is actually present |
|---|---|---|
| Module composition | `modules_statuses.json`, `config/modules.php`, `modules/*/module.json` | Agent, Client, Manager, Order, Permission, Tenant, Truck, User and supporting modules are enabled in the snapshot |
| Tenant and delivery domains | `modules/Tenant/`, `modules/Order/`, `modules/Agent/`, `modules/Truck/` | tenant/domain entities, migrations, repositories, services and Filament resources |
| Role-specific surfaces | `modules/*/Filament/Agent/` and `modules/*/Filament/Manager/` | separate operational/admin resource areas |
| API example | `modules/User/Routes/API/V1/` and `modules/User/Http/` | versioned profile routes, requests and resources |
| Automated checks | `modules/User/Tests/Feature/API/V1/Profile/ProfileControllerTest.php` plus PHPUnit tooling | one module feature test is present; a root `tests/` suite and deployment workflow are not present in this snapshot |

## Engineering Notes

### Modular domain separation
The repository separates operational areas into Laravel modules instead of concentrating delivery logic in the application root. This is useful for studying boundaries between tenant, order, agent, fleet and administration concerns.

### Multi-tenant SaaS structure
Tenant isolation is a first-class architectural concern, allowing multiple delivery businesses to operate through the same application structure while keeping company context separated.

### Local infrastructure
The repository includes Docker Compose/Sail-oriented local infrastructure. Deployment automation is not part of the current repository snapshot.

## Getting Started

The existing project is configured around Laravel Sail. At a high level:

```bash
composer install
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan module:migrate
```

The tracked frontend is the Mars theme. With Node.js/npm installed, build its Vite assets from the repository root:

```bash
npm run production
```

Additional module seeding, permission generation and frontend setup are required by the upstream application configuration. Inspect the module and environment configuration before running it; do not use example credentials in a public deployment.

## Repository Structure

```text
app/                 Laravel application shell
modules/             Domain modules
  Agent/
  Client/
  Manager/
  Order/
  Permission/
  Tenant/
  Truck/
  User/
themes/              Theme packages
database/            Application database setup
.env.example         Example application configuration
```

## Status

**Archive / replacement candidate for personal job-application use.** The software itself is a substantial full-stack Laravel codebase, but retained upstream attribution throughout the repository means it is weak evidence of Jason Lee's original engineering unless there is a clearly documented, substantial set of personal modifications that can be isolated and demonstrated.

## Provenance

The previous README, clone instructions, screenshots and module metadata identify the upstream project/author as `mohaphez/delivery-platform` / Mohaphez. That provenance should remain visible. A README rewrite alone does not make upstream code original work.

## License

The root Composer metadata declares MIT. Verify the upstream repository's complete license and attribution requirements before redistribution or modification of licensing material.

---

**Repository owner:** [@Masterleeaus](https://github.com/Masterleeaus)
