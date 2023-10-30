<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name'              => 'Manjurul Hasan',
                'email'             => 'shajib@gmail.com',
                'password'          => bcrypt('123456'),
            ]
        ];

        foreach ( $users as $user ) {
            $user = User::updateOrCreate(['email' => $user['email']], $user );
        }
    }
}
