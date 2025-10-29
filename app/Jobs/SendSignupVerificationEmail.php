<?php

namespace App\Jobs;

use App\Mail\SignupVerificationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendSignupVerificationEmail implements ShouldQueue
{
    use Queueable;


    protected $user;
    protected $token;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user)->send(new SignupVerificationEmail($this->user, $this->token));
    }
}
