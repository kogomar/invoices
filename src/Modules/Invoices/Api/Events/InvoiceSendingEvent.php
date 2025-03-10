<?php

namespace Modules\Invoices\Api\Events;

use Ramsey\Uuid\UuidInterface;

final readonly class InvoiceSendingEvent
{
    public function __construct(
        public string $customerEmail,
        public UuidInterface $referenceId,
    ) {}
}
