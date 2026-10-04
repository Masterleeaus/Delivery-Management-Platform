<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Modules\Base\Tests\BaseTestCase;
use Modules\User\Entities\V1\User;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

uses(BaseTestCase::class);

function registerTenantBoundaryProbe(?callable $onHit = null): void
{
    Route::middleware([
        'web',
        InitializeTenancyByDomain::class,
        PreventAccessFromCentralDomains::class,
    ])->get('/__tenant-boundary-probe', function () use ($onHit): string {
        if ($onHit !== null) {
            $onHit();
        }

        return 'tenant';
    });
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


it('fails unmapped tenant resolution before reaching the tenant route handler', function (): void {
    config([
        'tenancy.central_domains' => [
            'localhost',
            'center.test',
        ],
    ]);

    $probeReached = false;
    registerTenantBoundaryProbe(function () use (&$probeReached): void {
        $probeReached = true;
    });

    $this->withoutExceptionHandling();

    try {
        $this
            ->actingAs(new User())
            ->withServerVariables([
                'HTTP_HOST' => 'unmapped-tenant.test',
            ])
            // Keep the tenant host in the URI so the resolver exercises the requested host.
            ->get('http://unmapped-tenant.test/__tenant-boundary-probe');
    } catch (TenantCouldNotBeIdentifiedOnDomainException $exception) {
        expect($exception->getMessage())->toContain('unmapped-tenant.test');
        expect($probeReached)->toBeFalse();

        return;
    }

    $this->fail('Expected unmapped tenant domain resolution to throw before the route handler.');
});