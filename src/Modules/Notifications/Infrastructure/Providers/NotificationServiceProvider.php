<?php

declare(strict_types=1);

namespace Modules\Notifications\Infrastructure\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Modules\Notifications\Api\NotificationFacadeInterface;
use Modules\Notifications\Application\Facades\NotificationFacade;
use Modules\Notifications\Infrastructure\Drivers\DriverInterface;
use Modules\Notifications\Infrastructure\Drivers\DummyDriver;
use Psr\Log\LoggerInterface;

final class NotificationServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(DriverInterface::class, DummyDriver::class);

        $this->app->singleton(NotificationFacadeInterface::class, function ($app) {
            return new NotificationFacade(
                driver: $app->make(DriverInterface::class),
                logger: $app->make(LoggerInterface::class),
            );
        });
    }

    /** @return array<class-string> */
    public function provides(): array
    {
        return [
            DriverInterface::class,
            NotificationFacadeInterface::class,
        ];
    }
}
