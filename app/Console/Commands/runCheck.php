<?php

namespace App\Console\Commands;

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
//         $websites = [
//            'https://google.com' => 5, // Set a timeout of 5 seconds for example1.com
//            'https://vicafe.ch' => 10, // Set a timeout of 3 seconds for example2.com
//            'https://erp.vicafe.ch' => 10, // Set a timeout of 3 seconds for example2.com
//            'https://preprod-erp.vicafe.ch' => 10, // Set a timeout of 3 seconds for example2.com
//            'https://staging.vicafeerp.sls.ch' => 10, // Set a timeout of 3 seconds for example2.com
//            'https://shajib.ch' => 30, // Set a timeout of 3 seconds for example2.com
//            // Add the URLs and corresponding timeouts for the 200 websites you want to check here
//        ];
         $websites = [
             'https://google.com',
             'https://vicafe.ch',
             'https://erp.vicafe.ch',
             'https://preprod-erp.vicafe.ch',
             'https://staging.vicafeerp.sls.ch',
             'https://shajib.ch'
         ];

         $client = new Client();

         $requests = function ($websites) use ($client) {
             foreach ($websites as $url) {
                 yield function() use ($client, $url) {
                     return $client->getAsync($url);
                 };
             }
         };

         $pool = new Pool($client, $requests($websites), [
             'concurrency' => 20, // Adjust this value for the number of concurrent requests you want to make.
             'fulfilled' => function ($response, $index) use ($websites) {
                 if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                     $this->info($websites[$index] . " is up and running!");
                 } else {
                     $this->error($websites[$index] . " is down or inaccessible.");
                 }
             },
             'rejected' => function ($reason, $index) use ($websites) {
                 if($reason->getCode() !== 0){
                     $this->error($websites[$index] . " ". $reason->getResponse()->getReasonPhrase());
                 }else{
                    $this->error($websites[$index] . " ". $reason->getHandlerContext()['error']);
                 }

             },
         ]);

         $promise = $pool->promise();
         $promise->wait();
     }

}
