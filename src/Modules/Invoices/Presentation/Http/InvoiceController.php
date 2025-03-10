<?php

declare(strict_types=1);

namespace Modules\Invoices\Presentation\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Invoices\Application\DTO\CreateInvoiceParams;
use Modules\Invoices\Application\DTO\InvoiceResponse;
use Modules\Invoices\Application\Exceptions\InvoiceValidationException;
use Modules\Invoices\Application\Services\InvoiceCreationService;
use Modules\Invoices\Application\Services\InvoiceSendService;
use Modules\Invoices\Application\Services\InvoiceService;
use Modules\Invoices\Application\Validators\CreateInvoiceValidator;
use Modules\Invoices\Application\Validators\SendInvoiceValidator;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController
{
    public function __construct(
        private readonly InvoiceCreationService $invoiceCreationService,
        private readonly InvoiceService $invoiceService,
        private readonly InvoiceSendService $invoiceSendService,
        private readonly LoggerInterface $logger,
    ) {}

    public function show(string $invoiceId): JsonResponse
    {
        if (!Uuid::isValid($invoiceId)) {
            return response()->json(['error' => 'Invalid UUID format.'], Response::HTTP_BAD_REQUEST);
        }

        $invoice = $this->invoiceService->getInvoiceWithProductLines(Uuid::fromString($invoiceId));

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(InvoiceResponse::fromEntity($invoice), Response::HTTP_OK);
    }

    public function create(Request $request, CreateInvoiceValidator $validator): JsonResponse
    {
        $validated = $validator->validate($request->all());

        if ($validated->fails()) {
            return response()->json($validated->errors()->all(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $params = new CreateInvoiceParams(
            customerName: $validated['customer_name'],
            customerEmail: $validated['customer_email'],
        );

        try {
            $invoice = $this->invoiceCreationService->createInvoice($params);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return response()->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['status' => 'success', 'invoice' => $invoice->getId()], Response::HTTP_CREATED);
    }

    public function send(Request $request, SendInvoiceValidator $validator): JsonResponse
    {
        $validated = $validator->validate($request->all());

        if ($validated->fails()) {
            return response()->json($validated->errors()->all(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $invoice = $this->invoiceService
            ->getInvoiceWithProductLines(Uuid::fromString($validated->getData()['invoice_id']));

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found.'], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->invoiceSendService->sendInvoice($invoice);
        } catch (\ErrorException $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['success' => 'Invoice sending to the client'], Response::HTTP_NO_CONTENT);
    }
}
