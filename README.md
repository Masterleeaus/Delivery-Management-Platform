![Delivery Management Reference Application — ATTRIBUTED SOURCE REFERENCE](docs/images/portfolio-banner.svg)

# Delivery Management Platform

**A modular multi-tenant Laravel application for delivery-company administration, customer accounts, orders, agents and fleet operations.**

## Overview

This repository implements a conventional SaaS-style delivery-management system using Laravel modules. Its value is primarily as a reference for full-stack and backend architecture: tenant isolation, modular domain boundaries, role/permission infrastructure, order operations, administrative interfaces and container-oriented deployment.

The codebase is **not presented as an original greenfield implementation by Jason Lee**. It retains clear upstream Mohaphez authorship and project lineage in source metadata. That provenance materially affects its value as personal engineering evidence and should be considered before keeping it public for job applications.

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
- GitHub Actions deployment workflow and Dockerfile assets.

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
| Infrastructure | Laravel Sail/Docker, GitHub Actions |

## Engineering Notes

### Modular domain separation
The repository separates operational areas into Laravel modules instead of concentrating delivery logic in the application root. This is useful for studying boundaries between tenant, order, agent, fleet and administration concerns.

### Multi-tenant SaaS structure
Tenant isolation is a first-class architectural concern, allowing multiple delivery businesses to operate through the same application structure while keeping company context separated.

### Deployment automation
The repository includes Docker deployment assets and a GitHub Actions workflow intended to test, build and publish deployment artifacts.

## Getting Started

The existing project is configured around Laravel Sail. At a high level:

```bash
composer install
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan module:migrate
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
.github/             Deployment assets and workflow
```

## Status

**Archive / replacement candidate for personal job-application use.** The software itself is a substantial full-stack Laravel codebase, but retained upstream attribution throughout the repository means it is weak evidence of Jason Lee's original engineering unless there is a clearly documented, substantial set of personal modifications that can be isolated and demonstrated.

## Provenance

The previous README, clone instructions, screenshots and module metadata identify the upstream project/author as `mohaphez/delivery-platform` / Mohaphez. That provenance should remain visible. A README rewrite alone does not make upstream code original work.

## License

The root Composer metadata declares MIT. Verify the upstream repository's complete license and attribution requirements before redistribution or modification of licensing material.

---

**Repository owner:** [@Masterleeaus](https://github.com/Masterleeaus)
