<?php

namespace Modules\Invoices\Application\Validators\SendingValidators;

use Modules\Invoices\Domain\Entities\Invoice;

abstract class InvoiceValidationHandler implements LoggerHandler
{
    private ?LoggerHandler $nextHandler = null;

    public function setNext(LoggerHandler $handler): LoggerHandler
    {
        $this->nextHandler = $handler;

        return $handler;
    }

    public function handle(Invoice $invoice): void
    {
        $this->nextHandler?->handle($invoice);
    }
}
