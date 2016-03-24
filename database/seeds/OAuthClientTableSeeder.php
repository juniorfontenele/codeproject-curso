
<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OAuthClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = [
            'id' => 'appid1',
            'secret' => 'secret',
            'name' => 'APP1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        DB::table('oauth_clients')->truncate();
        DB::table('oauth_clients')->insert($client);
    }
}
