<?php

declare(strict_types=1);

namespace Modules\Notifications\Infrastructure\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Invoices\Api\Listeners\MarkInvoiceAsDeliveredListener;
use Modules\Notifications\Api\Events\ResourceDeliveredEvent;

class NotificationsEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ResourceDeliveredEvent::class => [
            MarkInvoiceAsDeliveredListener::class,
        ],
    ];
}
