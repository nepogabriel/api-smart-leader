<?php

namespace App\Jobs;

use App\Mail\TaskCreated;
use App\Mail\TaskCreatedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTaskEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public $user
    ) {}

    public function handle(): void
    {
        Mail::to($this->user->email)->send(new TaskCreatedMail($this->user));
    }
}
