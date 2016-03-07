<?php

use CodeProject\Entities\Project;
use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::truncate();
        factory(\CodeProject\Entities\Project::class, 10)->create();
    }
}
