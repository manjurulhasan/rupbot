<?php

namespace App\Console\Commands;

use App\Models\Site;
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

    private $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
    }

//    public function handle()
//    {
//        $websites = [
//            'https://google.com' => 5, // Set a timeout of 5 seconds for example1.com
//            'https://vicafe.ch' => 10, // Set a timeout of 3 seconds for example2.com
//            'https://erp.vicafe.ch' => 10, // Set a timeout of 3 seconds for example2.com
//            'https://preprod-erp.vicafe.ch' => 10, // Set a timeout of 3 seconds for example2.com
//            'https://staging.vicafeerp.sls.ch' => 10, // Set a timeout of 3 seconds for example2.com
//            'https://shajib.ch' => 30, // Set a timeout of 3 seconds for example2.com
//            // Add the URLs and corresponding timeouts for the 200 websites you want to check here
//        ];
//
//        $client = new Client();
//        $promises = [];
//
//        foreach ($websites as $url => $timeout) {
//            $promises[$url] = $this->isWebsiteUp($url, $client, $timeout);
//        }
//
//        $results = Utils::settle($promises)->wait();
//
//
//        foreach ($results as $url => $result) {
//            if ($result['state'] === 'fulfilled' && $result['value'] === true) {
//                $this->info($url . " is up and running!");
//            } else if ($result['state'] === 'fulfilled' && $result['value']->getCode() !== 0) {
////                dd($result['value']->getResponse()->getReasonPhrase());
//                $this->error($url . " ". $result['value']->getResponse()->getReasonPhrase());
//            }else{
////                $this->error($result['value']);
//                $this->error($url . " down". $result['value']->getHandlerContext()['error']);
//            }
//        }
//    }
//
//    private function isWebsiteUp($url, $client, $timeout)
//    {
//        return $client->getAsync($url, ['timeout' => $timeout])
//            ->then(
//                function ($response) {
//                    return $response->getStatusCode() >= 200 && $response->getStatusCode() < 300;
//                },
//                function ($reason) {
//                    return $reason;
//                }
//            );
//    }

     public function handle()
     {
        //  $websites = [
        //      'https://google.com',
        //      'https://vicafe.ch',
        //      'https://erp.vicafe.ch',
        //      'https://preprod-erp.vicafe.ch',
        //      'https://staging.vicafeerp.sls.ch',
        //      'https://shajib.ch'
        //  ];

         $websites = $this->getSites();
         $headers = [
             'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
             'Referer' => 'https://erp.vicafe.ch',
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
                Site::where('url', $websites[$index])->update($data);
            },
             'rejected' => function ($reason, $index) use ($websites) {
                if($reason->getCode() !== 0){
                    $status = $this->curlSite($websites[$index]);
                    if(!$status) {
                        $this->error($websites[$index] . " " . $reason->getResponse()->getReasonPhrase());
                        $data['message'] = $reason->getResponse()->getReasonPhrase();
                    }else{
                        $data['last_check'] = now();
                        $data['up_at']      = now();
                        $data['status']     = 1;
                        $data['code']       = 200;
                        $this->info($websites[$index] . " is up and running!");
                    }
                } else{
                    $this->error($websites[$index] . " ". $reason->getHandlerContext()['error']);
                    $data['message'] = $reason->getHandlerContext()['error'];
                }

                $data['code']       = $reason->getCode();
                $data['status']     = 0;
                $data['last_check'] = now();
                $data['down_at']    = now();
                Site::where('url', $websites[$index])->update($data);
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();
    }

    private function getSites(){
        return Site::query()->where('is_active', 1)->get()->pluck('url')->toArray();
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
