<?php

declare(strict_types=1);

namespace Modules\Notifications\Infrastructure\Drivers;

use Illuminate\Support\Facades\Http;

class DummyDriver implements DriverInterface
{
    public function send(
        string $toEmail,
        string $subject,
        string $message,
        string $reference,
    ): bool {
        $response = Http::get(env('APP_URL') . "/api/notification/hook/delivered/{$reference}");

        if ($response->successful()) {
            return true;
        }

        return false;
    }
}
