<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserSedder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'merchant',
            'email' => 'merchant@mail.com',
            'password' => Hash::make('secret'),
            'type' => 'merchant'
        ]);

        DB::table('users')->insert([
            'name' => 'consumer',
            'email' => 'consumer@mail.com',
            'password' => Hash::make('secret')
        ]);
    }
}
