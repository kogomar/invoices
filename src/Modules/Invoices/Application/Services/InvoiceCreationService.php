<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\Services;

use Modules\Invoices\Application\DTO\CreateInvoiceParams;
use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use Ramsey\Uuid\Uuid;

final readonly class InvoiceCreationService
{
    public function __construct(
        private InvoiceRepositoryInterface $repository
    ) {}

    public function createInvoice(CreateInvoiceParams $params): Invoice
    {
        $invoice = Invoice::create(Uuid::uuid4(), $params->customerName, $params->customerEmail);
        $this->repository->save($invoice);

        return $invoice;
    }
}
