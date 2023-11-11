<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\LastCheck;
use App\Models\Site;
use Exception;
use Illuminate\Support\Facades\DB;

class SiteManagerService
{
    public function getRows($filter): \Illuminate\Database\Eloquent\Builder
    {
        try
        {
        return Site::query()
            ->with(['contacts:site_id,email'])
            ->when($filter['site_name'], fn($q,$site_name ) => $q->where('project' , 'like' , "%$site_name%")->OrWhere('url', 'like', "%$site_name%")->OrWhere('manager', 'like', "%$site_name%") )
            ->latest();
        } catch (Exception $e){
            throw $e;
        }
    }
    public function getDashboardRows($filter): \Illuminate\Database\Eloquent\Builder
    {
        try
        {
            return Site::query()
                ->when($filter['site_name'], fn($q, $site_name) => $q->where('project', 'like', "%$site_name%")->OrWhere('url', 'like', "%$site_name%")->OrWhere('manager', 'like', "%$site_name%"))
                ->where('is_active', 1)
                ->latest();
        } catch (Exception $e){
            throw $e;
        }
    }
     public function addSite($site, $emails): bool
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
        try {
            $site = Site::query()->with(['contacts:id,site_id,email'])->find($site_id);
            $res['site'] = [
                'id'      => $site->id,
                'project' => $site->project,
                'url'     => $site->url,
                'manager' => $site->manager,
                'is_active' => $site->is_active
            ];
            $res['emails'] = [];
            foreach ($site->contacts as $contact){
                $res['emails'] []= [
                    'id' => $contact->id,
                    'email' => $contact->email,
                ];
            }
            return $res;
        } catch (Exception $e){
            throw $e;
        }
    }

    public function deleteSite($site_id)
    {
        try {
            return Site::query()->where('id',$site_id)->delete();
        } catch (Exception $e){
            throw $e;
        }
    }

    public function updateSite($site, $emails): bool
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
        try {
            return LastCheck::query()->select('last_check','next_check')->first();
        } catch(Exception $e) {
            throw $e;
        }
    }
}
