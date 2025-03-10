<?php

declare(strict_types=1);

namespace Modules\Invoices\Infrastructure\Repositories;

use Carbon\Carbon;
use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Enums\StatusEnum;
use Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Facades\DB;

final class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function save(Invoice $invoice): void
    {
        DB::table('invoices')->updateOrInsert(
            ['id' => $invoice->getId()],
            [
                'customer_name' => $invoice->getCustomerName(),
                'customer_email' => $invoice->getCustomerEmail(),
                'status' => $invoice->getStatus()->value,
                'updated_at' => Carbon::now(),
                'created_at' => DB::raw('COALESCE(created_at, NOW())'),
            ]
        );
    }

    public function findById(UuidInterface $id): ?Invoice
    {
        $invoice = DB::table('invoices')->where('id', $id->toString())->first();

        if (!$invoice) {
            return null;
        }

        return Invoice::create(
            id: $id,
            customerName: $invoice->customer_name,
            customerEmail: $invoice->customer_email,
        );
    }
}
