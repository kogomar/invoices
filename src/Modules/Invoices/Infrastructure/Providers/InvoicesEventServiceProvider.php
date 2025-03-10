<?php

declare(strict_types=1);

namespace Modules\Invoices\Infrastructure\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Invoices\Api\Events\InvoiceSendingEvent;
use Modules\Notifications\Api\Listeners\SendInvoiceNotificationListener;

final class InvoicesEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        InvoiceSendingEvent::class => [
            SendInvoiceNotificationListener::class,
        ],
    ];
}
