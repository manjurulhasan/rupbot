<?php

namespace App\Console\Commands;

use App\Jobs\MailSendJob;
use App\Models\Site;
use App\Services\CommandService;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\Utils;
use Illuminate\Console\Command;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class runCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'runCheck';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new CommandService();
    }

     public function handle()
     {
         $websites = $this->service->getSites();
         $headers = [
             'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
             'Referer' => 'https://google.com',
         ];

        $client = new Client([
            'headers' => $headers
        ]);

        $requests = function ($websites) use ($client) {
            foreach ($websites as $url) {
                yield function() use ($client, $url) {
                    return $client->getAsync($url);
                };
            }
        };

        $pool = new Pool($client, $requests($websites), [
            'concurrency' => config('rupbot.concurrency'),
            'fulfilled' => function ($response, $index) use ($websites) {
                if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                    $this->info($websites[$index] . " is up and running!");
                    $data['last_check'] = now();
                    $data['up_at']      = now();
                    $data['status']     = 1;
                    $data['code']       = $response->getStatusCode();
                } else {

                    $this->error($websites[$index] . " is down or inaccessible.");
                    $data['last_check'] = now();
                    $data['down_at']    = now();
                    $data['status']     = 0;
                    $data['code']       = $response->getStatusCode();
                }
                $this->service->updateSite($websites[$index] , $data);
            },
             'rejected' => function ($reason, $index) use ($websites) {
                if($reason->getCode() !== 0){
                    $status = $this->curlSite($websites[$index]);
                    if(!$status) {
                        $this->error($websites[$index] . " " . $reason->getResponse()->getReasonPhrase());
                        $data['message'] = $reason->getResponse()->getReasonPhrase();
                        $data['code']       = $reason->getCode();
                        $data['status']     = 0;
                        $data['last_check'] = now();
                        $data['down_at']    = now();
                    }else{
                        $data['last_check'] = now();
                        $data['up_at']      = now();
                        $data['status']     = 1;
                        $data['code']       = 200;
                        $this->info($websites[$index] . " is up and running!");
                    }
                } else{
                    $this->error($websites[$index] . " ". $reason->getHandlerContext()['error']);
                    $data['code']       = $reason->getCode();
                    $data['status']     = 0;
                    $data['last_check'] = now();
                    $data['down_at']    = now();
                    $data['message'] = $reason->getHandlerContext()['error'];
                }
                 $this->service->updateSite($websites[$index] , $data);
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();
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
