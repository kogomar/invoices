<?php

declare(strict_types=1);

namespace Tests\Unit\Invoices\Services;

use Illuminate\Events\Dispatcher;
use Modules\Invoices\Api\Events\InvoiceSendingEvent;
use Modules\Invoices\Application\Services\InvoiceSendService;
use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Entities\InvoiceProductLine;
use Modules\Invoices\Domain\Enums\StatusEnum;
use Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class InvoiceSendServiceTest extends TestCase
{
    public function testSendInvoice(): void
    {
        $repository = $this->createMock(InvoiceRepositoryInterface::class);
        $repository->expects($this->once())->method('save');

        $dispatcher = $this->createMock(Dispatcher::class);
        $dispatcher->expects($this->once())->method('dispatch')->with($this->isInstanceOf(InvoiceSendingEvent::class));

        $service = new InvoiceSendService($dispatcher, $repository);

        $invoice = Invoice::create(Uuid::uuid4(), 'Jim Halpert', 'halpert@example.com');
        $invoice->addProductLine(InvoiceProductLine::create(Uuid::uuid4(), $invoice->getId(), 'Product', 50, 1));

        $service->sendInvoice($invoice);

        $this->assertSame(StatusEnum::Sending, $invoice->getStatus());
    }
}
