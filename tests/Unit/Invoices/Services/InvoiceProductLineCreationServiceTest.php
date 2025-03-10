<?php

declare(strict_types=1);

namespace Tests\Unit\Invoices\Services;

use Modules\Invoices\Application\DTO\CreateInvoiceProductLineParams;
use Modules\Invoices\Application\Services\InvoiceProductLineCreationService;
use Modules\Invoices\Domain\Repositories\InvoiceProductLineRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class InvoiceProductLineCreationServiceTest extends TestCase
{
    public function testCreateInvoiceProductLine(): void
    {
        $repository = $this->createMock(InvoiceProductLineRepositoryInterface::class);
        $repository->expects($this->once())->method('save');

        $service = new InvoiceProductLineCreationService($repository);
        $params = new CreateInvoiceProductLineParams(Uuid::uuid4(), 'Advertisement', 100, 2);

        $productLine = $service->createInvoiceProductLine($params);

        $this->assertSame('Advertisement', $productLine->getName());
        $this->assertSame(100, $productLine->getPrice());
        $this->assertSame(2, $productLine->getQuantity());
    }
}
