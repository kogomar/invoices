<?php

namespace Modules\Invoices\Presentation\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Invoices\Application\DTO\CreateInvoiceProductLineParams;
use Modules\Invoices\Application\Services\InvoiceProductLineCreationService;
use Modules\Invoices\Application\Validators\CreateInvoiceProductLineValidator;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class InvoiceProductLineController
{
    public function __construct(
        private readonly InvoiceProductLineCreationService $invoiceProductLineCreationService,
        private readonly LoggerInterface $logger,
    ) {}

    public function create(Request $request, CreateInvoiceProductLineValidator $validator): JsonResponse
    {
        $validated = $validator->validate($request->all());

        if ($validated->fails()) {
            return response()->json($validated->errors()->all(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $params = new CreateInvoiceProductLineParams(
            invoiceId: Uuid::fromString($validated->getData()['invoice_id']),
            name: $validated->getData()['name'],
            price: $validated->getData()['price'],
            quantity: $validated->getData()['quantity'],

        );

        try {
            $invoiceProductLine = $this->invoiceProductLineCreationService->createInvoiceProductLine($params);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return response()->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()
            ->json(
            [
                'status' => 'success',
                'productLineId' => $invoiceProductLine->getId(),
            ],
            Response::HTTP_CREATED,
        );
    }
}
