<?php

declare(strict_types=1);

namespace Modules\Invoices\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Invoices\Domain\Repositories\InvoiceProductLineRepositoryInterface;
use Modules\Invoices\Infrastructure\Repositories\InvoiceProductLineRepository;

final class InvoiceProductLineServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            InvoiceProductLineRepositoryInterface::class,
            InvoiceProductLineRepository::class,
        );
    }

    public function provides(): array
    {
        return [InvoiceProductLineRepositoryInterface::class];
    }
}
