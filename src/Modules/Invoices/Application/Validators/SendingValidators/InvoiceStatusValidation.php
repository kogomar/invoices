<?php

namespace Modules\Invoices\Application\Validators\SendingValidators;

use Modules\Invoices\Application\Exceptions\InvoiceValidationException;
use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Enums\StatusEnum;

final class InvoiceStatusValidation extends InvoiceValidationHandler
{
    public function handle(Invoice $invoice): void
    {
        if ($invoice->getStatus() !== StatusEnum::Draft) {
            throw new InvoiceValidationException('Invoice must be in draft status to be sent.');
        }
    }
}
