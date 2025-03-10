<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\Services;

use Modules\Invoices\Application\DTO\CreateInvoiceProductLineParams;
use Modules\Invoices\Domain\Entities\InvoiceProductLine;
use Modules\Invoices\Domain\Repositories\InvoiceProductLineRepositoryInterface;
use Ramsey\Uuid\Uuid;

final readonly class InvoiceProductLineCreationService
{
    public function __construct(
        private InvoiceProductLineRepositoryInterface $repository
    ) {}

    public function createInvoiceProductLine(CreateInvoiceProductLineParams $params): InvoiceProductLine
    {
        $productLine = InvoiceProductLine::create(
            Uuid::uuid4(),
            $params->invoiceId,
            $params->name,
            $params->price,
            $params->quantity,
        );

        $this->repository->save($productLine);

        return $productLine;
    }
}
