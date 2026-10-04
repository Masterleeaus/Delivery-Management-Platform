<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Base\Tests\BaseTestCase;
use Modules\User\Entities\V1\User;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException;

uses(BaseTestCase::class);

it('rejects tenant entry points on a configured central domain', function (): void {
    config([
        'tenancy.central_domains' => [
            'localhost',
            'center.test',
        ],
    ]);

    $this->withServerVariables([
        'HTTP_HOST' => 'localhost',
    ])->get('/')->assertNotFound();
});

it('does not let an authenticated principal bypass tenant domain resolution', function (): void {
    config([
        'tenancy.central_domains' => [
            'localhost',
            'center.test',
        ],
    ]);

    $this->withoutExceptionHandling();

    expect(fn (): mixed => $this
        ->actingAs(new User())
        ->withServerVariables([
            'HTTP_HOST' => 'unmapped-tenant.test',
        ])
        ->get('/')
    )->toThrow(TenantCouldNotBeIdentifiedOnDomainException::class);
});
