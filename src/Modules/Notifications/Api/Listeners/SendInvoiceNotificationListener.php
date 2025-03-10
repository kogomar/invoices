<?php

declare(strict_types=1);

namespace Modules\Notifications\Api\Listeners;

use Modules\Invoices\Api\Events\InvoiceSendingEvent;
use Modules\Notifications\Api\Dtos\NotifyData;
use Modules\Notifications\Application\Facades\NotificationFacade;

final class SendInvoiceNotificationListener
{
    public function __construct(
        private readonly NotificationFacade $notificationFacade
    ) {}

    public function handle(InvoiceSendingEvent $event): void
    {
        $this->notificationFacade->notify(
            new NotifyData(
                resourceId: $event->referenceId,
                toEmail: $event->customerEmail,
                subject: 'Your Invoice ' . $event->referenceId->toString(),
                message: 'Your invoice is ready to view, please pay',
            )
        );
    }
}
