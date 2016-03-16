<?php

use Illuminate\Database\Seeder;

class ProjectMemberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('project_members')->truncate();
        for ($i=1;$i<=5;$i++) {
            $project = \CodeProject\Entities\Project::find($i);
            for ($j=1;$j<=3;$j++) {
                $user = \CodeProject\Entities\User::find($j);
                $project->members()->attach($user);
            }
        }
    }
}
