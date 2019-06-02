<?php

use Illuminate\Database\Seeder;
use App\User;

class InitialUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("users")->insert([
            [
                'name' => 'Bob "Loaded" Griller',
                'email' => 'admin@site.com',
                'password' => Hash::make('password'),
                'access_level' => User::ACCESS_LEVEL_ADMIN
            ],
            [
                'name' => 'Johnson "Burrito" Supreme',
                'email' => 'manager@site.com',
                'password' => Hash::make('password'),
                'access_level' => User::ACCESS_LEVEL_MANAGER
            ]
        ]);
    }
}
