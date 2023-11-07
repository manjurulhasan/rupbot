<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\LastCheck;
use App\Models\Site;
use Exception;
use Illuminate\Support\Facades\DB;

class SiteManagerService
{
     public function addSite($site, $emails)
     {
        try
        {
            DB::beginTransaction();
            $site = Site::firstOrCreate(
                ['url' => $site['url']],
                $site
            );

            foreach ($emails as $key => $email){
                if($email) {
                    Contact::updateOrCreate(
                        ['site_id' => $site->id, 'email' => $email['email']],
                        ['site_id' => $site->id, 'email' => $email['email']]
                    );
                }
            }
            DB::commit();
            return true;
        }
        catch(Exception $ex)
        {
            DB::rollBack();
            throw $ex;
        }
     }

    public function showSite($site_id): array
    {
        $site = Site::query()->with(['contacts:id,site_id,email'])->find($site_id);
        $res['site'] = [
            'id'      => $site->id,
            'project' => $site->project,
            'url'     => $site->url,
            'manager' => $site->manager
        ];
        $res['emails'] = [];
        foreach ($site->contacts as $contact){
            $res['emails'] []= [
                'id' => $contact->id,
                'email' => $contact->email,
            ];
        }
        return $res;
    }

    public function deleteSite($site_id)
    {
        try {
            return Site::query()->where('id',$site_id)->delete();
        } catch (Exception $e){
            throw $e;
        }
    }

    public function updateSite($site, $emails)
    {
        try {
            DB::beginTransaction();
            Site::query()->where('id',$site['id'])->update($site);
            foreach ($emails as $email){
                $data['id']      = $email['id'];
                $data['site_id'] = $site['id'];
                $data['email']   = $email['email'];
                Contact::updateOrCreate(
                    ['id' => $data['id']], $data
                );
            }
            DB::commit();
            return true;
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function lastCheck()
    {
        return LastCheck::query()->select('last_check','next_check')->first();
    }
}
