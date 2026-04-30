<?php

namespace App\Jobs;

use App\Mail\NonCompliantEmail;
use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendNonCompliantEmailJob implements ShouldQueue
{
    use Queueable;
    public $task;
    /**
     * Create a new job instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to('yousuf802@gmail.com')->send(new NonCompliantEmail($this->task));
    }
}
