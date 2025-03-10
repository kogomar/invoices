<?php

declare(strict_types=1);

namespace Modules\Invoices\Api\Listeners;

use Modules\Invoices\Application\Services\InvoiceService;
use Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use Modules\Notifications\Api\Events\ResourceDeliveredEvent;
use Psr\Log\LoggerInterface;

final class MarkInvoiceAsDeliveredListener
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly LoggerInterface $logger,
        private readonly InvoiceService $invoiceService,
    ) {}

    public function handle(ResourceDeliveredEvent $event): void
    {
        $invoice = $this->invoiceRepository->findById($event->resourceId);

        if (!$invoice) {
            $this->logger->error("Invoice $event->resourceId has not been found in MarkInvoiceAsDeliveredListener");
            return;
        }
        $this->logger->error("Invoice $event->resourceId marked as sent-to-client");

        try {
            $this->invoiceService->markAsSentToClient($invoice);
            $this->logger->error("Invoice $event->resourceId marked as sent-to-client");
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}
