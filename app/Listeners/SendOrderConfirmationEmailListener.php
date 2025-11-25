<?php

namespace App\Listeners;

use App\Events\SalesOrderCreatedEvent;
use App\Mail\SalesOrderCreatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderConfirmationEmailListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SalesOrderCreatedEvent $event): void
    {
        Mail::queue(
            new SalesOrderCreatedMail($event->sales_order)
        );
    }
}
