<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '木村拓己',
            'email' => 'tales111217@gmail.com',
            'password' => '1995taku'
        ];
        DB::table('Registration')->insert($param);
    }
}
