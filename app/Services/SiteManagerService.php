<?php

namespace App\Services;

use App\Models\Site;
use Exception;

class SiteManagerService
{
     public function addSite($site)
     {
        try
        {
            return Site::firstOrCreate(
                ['url' => $site['url']],
                $site
            );
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
     }
}
