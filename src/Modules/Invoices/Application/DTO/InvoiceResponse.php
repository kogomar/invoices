<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\DTO;

use Modules\Invoices\Domain\Entities\Invoice;

final class InvoiceResponse
{
    public function __construct(
        public string $invoiceId,
        public string $status,
        public string $customerName,
        public string $customerEmail,
        public int $totalPrice,
        public array $productLines
    ) {}

    public static function fromEntity(Invoice $invoice): self
    {
        return new self(
            invoiceId: $invoice->getId()->toString(),
            status: $invoice->getStatus()->value,
            customerName: $invoice->getCustomerName(),
            customerEmail: $invoice->getCustomerEmail(),
            totalPrice: $invoice->getTotalPrice(),
            productLines: array_map(static fn ($line) => [
                'product_name' => $line->getName(),
                'quantity' => $line->getQuantity(),
                'unit_price' => $line->getPrice(),
                'total_unit_price' => $line->getTotalPrice(),
            ], $invoice->getProductLines())
        );
    }
}
