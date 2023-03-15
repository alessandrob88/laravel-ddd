<?php

namespace App\Providers;

use App\Events\InvoiceCancel;
use App\Events\InvoiceCreate;
use App\Events\InvoiceRowCreate;
use App\Events\InvoiceRowUpdate;
use App\Listeners\InvoiceCancelEventListener;
use App\Listeners\InvoiceCreateEventListener;
use App\Listeners\InvoiceRowCreateEventListener;
use App\Listeners\InvoiceRowUpdateEventListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        InvoiceCreate::class => [
            InvoiceCreateEventListener::class,
        ],
        InvoiceCancel::class => [
            InvoiceCancelEventListener::class,
        ],
        InvoiceRowCreate::class => [
            InvoiceRowCreateEventListener::class,
        ],
        InvoiceRowUpdate::class => [
            InvoiceRowUpdateEventListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
