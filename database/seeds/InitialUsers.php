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
        $user = new User([
            'name' => 'Bob "Loaded" Griller',
            'email' => 'admin@site.com',
            'password' => Hash::make('password'),
            'access_level' => User::ACCESS_LEVEL_ADMIN
        ]);
        
        $user->save();
        
        $user = new User([
            'name' => 'Johnson "Burrito" Supreme',
            'email' => 'manager@site.com',
            'password' => Hash::make('password'),
            'access_level' => User::ACCESS_LEVEL_MANAGER
        ]);
        
        $user->save();
    }
}
