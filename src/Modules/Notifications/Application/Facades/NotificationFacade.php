<?php

declare(strict_types=1);

namespace Modules\Notifications\Application\Facades;

use Modules\Notifications\Api\Dtos\NotifyData;
use Modules\Notifications\Api\NotificationFacadeInterface;
use Modules\Notifications\Infrastructure\Drivers\DriverInterface;
use Psr\Log\LoggerInterface;

final readonly class NotificationFacade implements NotificationFacadeInterface
{
    public function __construct(
        private DriverInterface $driver,
        private LoggerInterface $logger
    ) {}

    public function notify(NotifyData $data): void
    {
        try {
            if ($this->driver->send(
                toEmail: $data->toEmail,
                subject: $data->subject,
                message: $data->message,
                reference: $data->resourceId->toString()
            )) {
                $this->logger->info('Invoice successfully sent to the ' . $data->toEmail);
                return;
            }
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        throw new \RuntimeException('All notification drivers failed.');
    }
}
