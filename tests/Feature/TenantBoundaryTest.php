<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Modules\Base\Tests\BaseTestCase;
use Modules\User\Entities\V1\User;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

uses(BaseTestCase::class);

function registerTenantBoundaryProbe(): void
{
    Route::middleware([
        'web',
        InitializeTenancyByDomain::class,
        PreventAccessFromCentralDomains::class,
    ])->get('/__tenant-boundary-probe', fn (): string => 'tenant');
}

it('rejects tenant entry points on a configured central domain', function (): void {
    config([
        'tenancy.central_domains' => [
            'localhost',
            'center.test',
        ],
    ]);

    registerTenantBoundaryProbe();

    $this->withServerVariables([
        'HTTP_HOST' => 'localhost',
    ])->get('/__tenant-boundary-probe')->assertNotFound();
});

it('does not let an authenticated principal bypass tenant domain resolution', function (): void {
    config([
        'tenancy.central_domains' => [
            'localhost',
            'center.test',
        ],
    ]);

    registerTenantBoundaryProbe();

    $response = $this
        ->actingAs(new User())
        ->withServerVariables([
            'HTTP_HOST' => 'unmapped-tenant.test',
        ])
        // Keep the tenant host in the URI so the resolver exercises the requested host.
        ->get('http://unmapped-tenant.test/__tenant-boundary-probe');

    $response->assertStatus(500);
});
