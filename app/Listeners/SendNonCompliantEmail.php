<?php

namespace App\Listeners;

use App\Events\TaskNonCompliant;
use App\Jobs\SendNonCompliantEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNonCompliantEmail
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
    public function handle(TaskNonCompliant $event): void
    {
        dispatch(new SendNonCompliantEmailJob($event->task));
    }
}
