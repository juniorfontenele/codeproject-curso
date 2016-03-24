<?php

use CodeProject\Entities\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        factory(CodeProject\Entities\User::class)->create(['email' => 'a@a.com']);
        factory(CodeProject\Entities\User::class)->create(['email' => 'b@b.com']);
        factory(CodeProject\Entities\User::class)->create(['email' => 'c@c.com']);
        factory(CodeProject\Entities\User::class,10)->create();
    }
}
