<?php

namespace App\Services;

use App\Jobs\MailSendJob;
use App\Models\Log;
use App\Models\Site;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CommandService
{
    public function getSites()
    {
        return Site::query()->where('is_active', 1)->get()->pluck('url')->toArray();
//        return Cache::remember('sites', 60*60*24, function () {
//            Site::query()->where('is_active', 1)->get()->pluck('url')->toArray();
//        });
    }

     public function updateSite($url, $data)
     {
        try
        {
            $site = Site::query()->with(['contacts'])->where('url', $url)->first();
            DB::beginTransaction();
            $mail = $data;
            $mail['contacts'] = $site->contacts;
            $mail['site']     = $site;
            if($site->status == 1 && $data['status'] == 0){
                MailSendJob::dispatch($mail);
            }
            $site->update($data);
            $data['site_id'] = $site->id;
            $data['url'] = $site->url;
            Log::create($data);
            DB::commit();
            return true;
        }
        catch(Exception $ex)
        {
            DB::rollBack();
            throw $ex;
        }
     }
}
