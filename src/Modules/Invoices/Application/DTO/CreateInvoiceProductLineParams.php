<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\DTO;

use Ramsey\Uuid\UuidInterface;

final class CreateInvoiceProductLineParams
{
    public function __construct(
        public UuidInterface $invoiceId,
        public string $name,
        public int $price,
        public int $quantity,
    ) {}
}
