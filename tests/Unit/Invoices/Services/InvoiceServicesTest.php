<?php

declare(strict_types=1);

namespace Tests\Unit\Invoices\Services;

use Modules\Invoices\Application\DTO\CreateInvoiceParams;
use Modules\Invoices\Application\Services\InvoiceCreationService;
use Modules\Invoices\Application\Services\InvoiceService;
use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Enums\StatusEnum;
use Modules\Invoices\Domain\Repositories\InvoiceProductLineRepositoryInterface;
use Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class InvoiceServicesTest extends TestCase
{
    public function testCreateInvoice(): void
    {
        $repository = $this->createMock(InvoiceRepositoryInterface::class);
        $repository->expects($this->once())->method('save');

        $service = new InvoiceCreationService($repository);
        $params = new CreateInvoiceParams('Michael Scott', 'michael@gmail.com');

        $invoice = $service->createInvoice($params);

        $this->assertSame('Michael Scott', $invoice->getCustomerName());
        $this->assertSame('michael@gmail.com', $invoice->getCustomerEmail());
        $this->assertSame(StatusEnum::Draft, $invoice->getStatus());
    }

    public function testGetInvoiceWithProductLines(): void
    {
        $invoiceId = Uuid::uuid4();

        $invoiceRepository = $this->createMock(InvoiceRepositoryInterface::class);
        $invoiceRepository->method('findById')->willReturn(Invoice::create($invoiceId, 'Michael Scott', 'scott@example.com'));

        $productLineRepository = $this->createMock(InvoiceProductLineRepositoryInterface::class);
        $productLineRepository->method('findByInvoiceId')->willReturn(collect([
            (object)['id' => Uuid::uuid4(), 'name' => 'Product 1', 'price' => 100, 'quantity' => 2],
            (object)['id' => Uuid::uuid4(), 'name' => 'Product 2', 'price' => 50, 'quantity' => 3],
        ]));

        $service = new InvoiceService($invoiceRepository, $productLineRepository);

        $invoice = $service->getInvoiceWithProductLines($invoiceId);

        $this->assertNotNull($invoice);
        $this->assertCount(2, $invoice->getProductLines());
        $this->assertSame('Product 1', $invoice->getProductLines()[0]->getName());
    }

    public function testMarkAsSentToClient(): void
    {
        $invoiceRepository = $this->createMock(InvoiceRepositoryInterface::class);
        $invoiceRepository->expects($this->once())->method('save');

        $invoice = Invoice::create(Uuid::uuid4(), 'Michael Scott', 'scott@example.com');
        $invoice->setStatus(StatusEnum::Sending);

        $service = new InvoiceService($invoiceRepository, $this->createMock(InvoiceProductLineRepositoryInterface::class));

        $service->markAsSentToClient($invoice);

        $this->assertSame(StatusEnum::SentToClient, $invoice->getStatus());
    }
}
