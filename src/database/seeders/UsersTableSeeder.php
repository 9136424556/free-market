<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'COACHTECH',
            'email' => 'coachtech@coachtech.com',
            'password' => Hash::make('coachtech'),
            'img_url'=> '',
            'introduction' => '',
            'role' => 'admin',
            'remember_token' => ''
        ];
        DB::table('users')->insert($param);
    }
}
