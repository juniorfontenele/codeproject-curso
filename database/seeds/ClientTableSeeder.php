<?php

use CodeProject\Entities\Client;
use Illuminate\Database\Seeder;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::truncate();
	    factory(\CodeProject\Entities\Client::class, 10)->create();
    }
}
