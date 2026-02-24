<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        \App\Events\UserFollowed::class => [
            \App\Listeners\SendNewFollowerNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}