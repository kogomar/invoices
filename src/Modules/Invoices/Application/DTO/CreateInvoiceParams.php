<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\DTO;

final class CreateInvoiceParams
{
    public function __construct(
        public string $customerName,
        public string $customerEmail
    ) {}
}
