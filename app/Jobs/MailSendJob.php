<?php

namespace App\Jobs;

use App\Mail\SiteDownMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class MailSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payload;

    /**
     * Create a new job instance.
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $contacts = $this->payload['contacts'];
        if(count($contacts) > 0){
            foreach ($contacts as $contact){
                Mail::to($contact->email)->send(new SiteDownMail($this->payload));
            }
        }
//        Mail::to('shajib@gmail.com')->send(new SiteDownMail($this->payload));

    }
}
