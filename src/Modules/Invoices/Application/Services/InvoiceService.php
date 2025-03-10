<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\Services;

use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Entities\InvoiceProductLine;
use Modules\Invoices\Domain\Enums\StatusEnum;
use Modules\Invoices\Domain\Repositories\InvoiceProductLineRepositoryInterface;
use Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final readonly class InvoiceService
{
    public function __construct(
        private InvoiceRepositoryInterface $invoiceRepository,
        private InvoiceProductLineRepositoryInterface $productLineRepository,
    ) {}

    public function getInvoiceWithProductLines(UuidInterface $id): ?Invoice
    {
        $invoice = $this->invoiceRepository->findById($id);

        if (!$invoice) {
            return null;
        }

        foreach ($this->productLineRepository->findByInvoiceId($invoice->getId()) as $line) {
            $invoice->addProductLine(new InvoiceProductLine(
                id: $line->id,
                invoiceId: $id,
                name: $line->name,
                price: $line->price,
                quantity: $line->quantity
            ));
        }

        return $invoice;
    }

    public function markAsSentToClient(Invoice $invoice): void
    {
        if ($invoice->getStatus() !== StatusEnum::Sending) {
            throw new \InvalidArgumentException('Invoice must be in sending status to be marked as sent-to-client.');
        }

        $invoice->setStatus(StatusEnum::SentToClient);
        $this->invoiceRepository->save($invoice);
    }
}
