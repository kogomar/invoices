<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\Validators\SendingValidators;

use Modules\Invoices\Domain\Entities\Invoice;

interface LoggerHandler
{
    public function setNext(LoggerHandler $handler): LoggerHandler;
    public function handle(Invoice $invoice): void;
}
