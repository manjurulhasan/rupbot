<?php

namespace App\Services;

use App\Jobs\MailSendJob;
use App\Jobs\SiteUpMailSendJob;
use App\Models\LastCheck;
use App\Models\Log;
use App\Models\Site;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class CommandService
{
    public function getSites(): array
    {
        $this->updateLastCheck();
        return Site::query()->where('is_active', 1)->get()->pluck('url')->toArray();
    }

    public function updateLastCheck()
    {
        return LastCheck::updateOrCreate([ 'id'=>1 ],['last_check' => now(), 'next_check' => now()->addMinute()]);
    }

     public function updateSite($url, $data): bool
     {
        try
        {
            $site = Site::query()->with(['contacts'])->where('url', $url)->first();
//            DB::beginTransaction();
            if(($site->status == 1 && $data['status'] == 1) || ($site->status == 0 && $data['status'] == 0)){
                return true;
            }

            if($site->status == 0 && $data['status'] == 1){
                $down_at = Carbon::parse($site->down_at);
                $up_at   = Carbon::parse($data['up_at']);
                $diff    = $down_at->diff($up_at)->format('%H:%I:%S');
                $data['duration'] = $diff;
                $mail   = $data;
                $mail['contacts'] = $site->contacts;
                $mail['site']     = $site;
                SiteUpMailSendJob::dispatch($mail);
            }

            if($site->status == 1 && $data['status'] == 0){
                $mail = $data;
                $mail['contacts'] = $site->contacts;
                $mail['site']     = $site;
                MailSendJob::dispatch($mail);
            }

            $site->update($data);

            $data['site_id'] = $site->id;
            $data['url']     = $site->url;
            Log::create($data);
//            DB::commit();
            return true;
        }
        catch(Exception $ex)
        {
//            DB::rollBack();
            throw $ex;
        }
     }
}
