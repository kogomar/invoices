<?php

declare(strict_types=1);

namespace Tests\Unit\Notification\Facades;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Modules\Notifications\Api\Dtos\NotifyData;
use Modules\Notifications\Application\Facades\NotificationFacade;
use Modules\Notifications\Infrastructure\Drivers\DriverInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class NotificationFacadeTest extends TestCase
{
    use WithFaker;

    private DriverInterface $driver;

    private NotificationFacade $notificationFacade;

    protected function setUp(): void
    {
        $this->setUpFaker();

        $this->driver = $this->createMock(DriverInterface::class);
        $this->driver->method('send')->willReturn(true);

        $this->notificationFacade = new NotificationFacade(
            driver: $this->driver,
            logger: $this->createMock(LoggerInterface::class)
        );
    }

    public function testDelivered(): void
    {
        $data = new NotifyData(
            resourceId: Str::uuid(),
            toEmail: $this->faker->email(),
            subject: $this->faker->sentence(),
            message: $this->faker->sentence(),
        );

        $this->driver->expects($this->once())->method('send');

        $this->notificationFacade->notify($data);
    }
}
