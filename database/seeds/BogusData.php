<?php

use Illuminate\Database\Seeder;

class BogusData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Company::class, 50)->create()->each(function ($company) {
            $company->employees()->saveMany(factory(App\Employee::class, 10)->make());
        });
    }
}
