<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['guard_name' => 'web', 'name' => 'Super-Admin'],
            ['guard_name' => 'web', 'name' => 'Admin'],
            ['guard_name' => 'web', 'name' => 'Viewer'],
        ];
        Role::upsert($roles , 'name');
    }
}
