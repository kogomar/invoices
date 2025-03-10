<?php

namespace Modules\Invoices\Domain\Repositories;

use Illuminate\Support\Collection;
use Modules\Invoices\Domain\Entities\InvoiceProductLine;
use Ramsey\Uuid\UuidInterface;

interface InvoiceProductLineRepositoryInterface
{
    public function save(InvoiceProductLine $productLine): void;
    public function findById(UuidInterface $id): ?InvoiceProductLine;
    public function findByInvoiceId(UuidInterface $id): Collection;
}
