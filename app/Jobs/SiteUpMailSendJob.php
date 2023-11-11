<?php

namespace App\Jobs;

use App\Mail\SiteUpMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SiteUpMailSendJob implements ShouldQueue
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
//        Mail::to('shajib@gmail.com')->send(new SiteUpMail($this->payload));
        $contacts = $this->payload['contacts'];
        if(count($contacts) > 0){
            foreach ($contacts as $contact){
                Mail::to($contact['email'])->send(new SiteUpMail($this->payload));
            }
        }
    }
}
