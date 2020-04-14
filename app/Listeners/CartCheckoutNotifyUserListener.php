<?php

namespace App\Listeners;

use App\Events\CartCheckoutEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CartCheckoutNotifyUserListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CartCheckoutEvent $event)
    {
        \Log::error($event->order->user->email);
    }
}
