<?php

namespace App\Livewire;

use App\Jobs\MailSendJob;
use App\Mail\SiteDownMail;
use App\Models\Site;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Test extends BaseComponent
{
    public function render()
    {
        return $this->view('livewire.test', []);
    }
//    public function mail(){
//        $mail = [];
//        MailSendJob::dispatch($mail);
//    }
    public function mail()
    {
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Product', 'message' => 'HI']);
        //  $websites = [
        //      'https://google.com',
        //      'https://vicafe.ch',
        //      'https://erp.vicafe.ch',
        //      'https://preprod-erp.vicafe.ch',
        //      'https://staging.vicafeerp.sls.ch',
        //      'https://shajib.ch'
        //  ];

//        $websites = $this->getSites();
//        $headers = [
//            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
//            'Referer' => 'https://google.com',
//        ];
//
//        $client = new Client([
//            'headers' => $headers
//        ]);
//
//        $requests = function ($websites) use ($client) {
//            foreach ($websites as $url) {
//                yield function() use ($client, $url) {
//                    return $client->getAsync($url);
//                };
//            }
//        };

//        $pool = new Pool($client, $requests($websites), [
//            'concurrency' => config('rupbot.concurrency'),
//            'fulfilled' => function ($response, $index) use ($websites) {
//                if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
////                    $this->info($websites[$index] . " is up and running!");
//                    $data['last_check'] = now();
//                    $data['up_at']      = now();
//                    $data['status']     = 1;
//                    $data['code']       = $response->getStatusCode();
//                } else {
//
////                    $this->error($websites[$index] . " is down or inaccessible.");
//                    $data['last_check'] = now();
//                    $data['down_at']    = now();
//                    $data['status']     = 0;
//                    $data['code']       = $response->getStatusCode();
//                }
//                $this->updateSite($websites[$index] , $data);
//            },
//            'rejected' => function ($reason, $index) use ($websites) {
//                if($reason->getCode() !== 0){
//                    $status = $this->curlSite($websites[$index]);
//                    if(!$status) {
////                    $this->error($websites[$index] . " " . $reason->getResponse()->getReasonPhrase());
//                    $data['message'] = $reason->getResponse()->getReasonPhrase();
//                    $data['code']       = $reason->getCode();
//                    $data['status']     = 0;
//                    $data['last_check'] = now();
//                    $data['down_at']    = now();
//                    }else{
//                        $data['last_check'] = now();
//                        $data['up_at']      = now();
//                        $data['status']     = 1;
//                        $data['code']       = 200;
////                        $this->info($websites[$index] . " is up and running!");
//                    }
//                } else{
////                    $this->error($websites[$index] . " ". $reason->getHandlerContext()['error']);
//                    $data['code']       = $reason->getCode();
//                    $data['status']     = 0;
//                    $data['last_check'] = now();
//                    $data['down_at']    = now();
//                    $data['message'] = $reason->getHandlerContext()['error'];
//                }
//                $this->updateSite($websites[$index] , $data);
//            },
//        ]);
//
//        $promise = $pool->promise();
//        $promise->wait();
    }

    private function getSites(){
        return Site::query()->where('url', 'https://vicafe.ch')->get()->pluck('url')->toArray();
    }

    private function updateSite($url, $data)
    {
        $site = Site::query()->with(['contacts'])->where('url', $url)->first();
        $mail = $data;
        $mail['contacts'] = $site->contacts;

        if($site->status == 1 && $data['status'] == 0){
            MailSendJob::dispatch($mail);
        }
        $site->update($data);
        return true;
    }

    private function curlSite($url)
    {
        $ch = curl_init($url);
        $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36';

        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
//        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'HEAD');
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($response !== false && $httpCode === 200) {
            return true;
        } else {
            return false;
        }
        curl_close($ch);
    }
}
