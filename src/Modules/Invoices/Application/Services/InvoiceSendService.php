<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\Services;

use Illuminate\Events\Dispatcher;
use Modules\Invoices\Api\Events\InvoiceSendingEvent;
use Modules\Invoices\Application\Validators\SendingValidators\InvoiceProductLinesValidation;
use Modules\Invoices\Application\Validators\SendingValidators\InvoiceStatusValidation;
use Modules\Invoices\Application\Validators\SendingValidators\LoggerHandler;
use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Enums\StatusEnum;
use Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;

final readonly class InvoiceSendService
{
    public function __construct(
        private Dispatcher $eventDispatcher,
        private InvoiceRepositoryInterface $repository
    ) {}

    public function sendInvoice(Invoice $invoice): void
    {
        $this->createValidationChain()->handle($invoice);

        $this->eventDispatcher->dispatch(new InvoiceSendingEvent(
            customerEmail: $invoice->getCustomerEmail(),
            referenceId: $invoice->getId(),
        ));

        $invoice->setStatus(StatusEnum::Sending);
        $this->repository->save($invoice);
    }

    private function createValidationChain(): LoggerHandler
    {
        $statusValidation = new InvoiceStatusValidation();
        $statusValidation->setNext(new InvoiceProductLinesValidation());

        return $statusValidation;
    }
}
