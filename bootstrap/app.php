<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Modules\Invoices\Infrastructure\Providers\InvoiceProductLineServiceProvider;
use Modules\Invoices\Infrastructure\Providers\InvoiceServiceProvider;
use Modules\Invoices\Infrastructure\Providers\InvoicesEventServiceProvider;
use Modules\Notifications\Infrastructure\Providers\NotificationServiceProvider;
use Modules\Notifications\Infrastructure\Providers\NotificationsEventServiceProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders(
        [
            InvoiceServiceProvider::class,
            InvoiceProductLineServiceProvider::class,
            InvoicesEventServiceProvider::class,
            NotificationServiceProvider::class,
            NotificationsEventServiceProvider::class,
        ]
    )
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
