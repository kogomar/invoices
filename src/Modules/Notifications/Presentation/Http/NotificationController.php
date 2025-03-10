<?php

declare(strict_types=1);

namespace Modules\Notifications\Presentation\Http;

use Illuminate\Http\JsonResponse;
use Modules\Notifications\Application\Services\NotificationService;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

final readonly class NotificationController
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function hook(string $action, string $resourceId): JsonResponse
    {
        match ($action) {
            'delivered' => $this->notificationService->delivered(reference: $resourceId),
            default => null,
        };

        if (!Uuid::isValid($resourceId)) {
            return response()->json(['error' => 'Invalid UUID format.'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(data: null, status: Response::HTTP_OK);
    }
}
