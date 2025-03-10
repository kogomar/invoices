<?php

declare(strict_types=1);

namespace Modules\Invoices\Infrastructure\Repositories;

use Carbon\Carbon;
use Modules\Invoices\Domain\Entities\InvoiceProductLine;
use Modules\Invoices\Domain\Repositories\InvoiceProductLineRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

final class InvoiceProductLineRepository implements InvoiceProductLineRepositoryInterface
{
    public function save(InvoiceProductLine $productLine): void
    {
        DB::table('invoice_product_lines')->updateOrInsert(
            ['id' => $productLine->getId()->toString()],
            [
                'invoice_id' => $productLine->getInvoiceId()->toString(),
                'name' => $productLine->getName(),
                'price' => $productLine->getPrice(),
                'quantity' => $productLine->getQuantity(),
                'updated_at' => Carbon::now(),
                'created_at' => DB::raw('COALESCE(created_at, NOW())'),
            ]
        );
    }

    public function findById(UuidInterface $id): ?InvoiceProductLine
    {
        $productLine = DB::table('invoice_product_lines')
            ->where('id', $id->toString())
            ->first();

        if (!$productLine) {
            return null;
        }

        return new InvoiceProductLine(
            id: $id,
            invoiceId: Uuid::fromString($productLine->invoice_id),
            name: $productLine->name,
            price: $productLine->price,
            quantity: $productLine->quantity,
        );
    }

    public function findByInvoiceId(UuidInterface $id): Collection
    {
        return DB::table('invoice_product_lines')
            ->where('invoice_id', $id->toString())
            ->get();
    }
}
