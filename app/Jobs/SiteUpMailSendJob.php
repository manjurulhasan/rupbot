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
            $emails = [];
            foreach ($contacts as $contact){
                $emails []= $contact['email'];
            }
            $admin_emails =  count($this->payload['admin_emails']) > 0 ? $this->payload['admin_emails'] : config('rupbot.admin_email');
            Mail::to($emails)->cc($admin_emails)->send(new SiteUpMail($this->payload));
        }
    }
}
