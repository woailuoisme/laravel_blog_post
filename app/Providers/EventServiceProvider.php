<?php

namespace App\Providers;

use App\Events\CartCheckoutEvent;
use App\Events\OrderPaidEvent;
use App\Listeners\CartCheckoutNotifyUserListener;
use App\Listeners\OrderPaidNotifyUserListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderPaidEvent::class =>[
            OrderPaidNotifyUserListener::class
        ],
        CartCheckoutEvent::class=>[
            CartCheckoutNotifyUserListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
