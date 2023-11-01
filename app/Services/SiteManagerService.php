<?php

namespace App\Services;

use App\Models\Contact;
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
        }
        catch(Exception $ex)
        {
            DB::rollBack();
            throw $ex;
        }
     }
}
