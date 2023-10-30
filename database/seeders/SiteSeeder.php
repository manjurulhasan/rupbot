<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sites = [
            [
                'project'       => 'Vicafe ERP',
                'url'           => 'https://erp.vicafe.ch',
                'manager'       => 'Manjurul Hasan',
                'is_active'     => 1,
            ],
            [
                'project'       => 'Vicafe ERP Preprod',
                'url'           => 'https://preprod-erp.vicafe.ch',
                'manager'       => 'Manjurul Hasan',
                'is_active'     => 1,
            ],
            [
                'project'       => 'Vicafe ERP Stage',
                'url'           => 'https://staging.vicafeerp.sls.ch',
                'manager'       => 'Manjurul Hasan',
                'is_active'     => 1,
            ],
            [
                'project'       => 'Vicafe Eshop',
                'url'           => 'https://vicafe.ch',
                'manager'       => 'Manjurul Hasan',
                'is_active'     => 1,
            ]
        ];

        foreach ( $sites as $site ) {
            Site::updateOrCreate(['url' => $site['url']], $site );
        }
    }
}
