<?php

namespace Modules\Invoices\Application\Validators\SendingValidators;


use Modules\Invoices\Application\Exceptions\InvoiceValidationException;
use Modules\Invoices\Domain\Entities\Invoice;

final class InvoiceProductLinesValidation extends InvoiceValidationHandler
{
    public function handle(Invoice $invoice): void
    {
        if (empty($invoice->getProductLines())) {
            throw new InvoiceValidationException('Invoice must contain product lines before sending.');
        }
    }
}
