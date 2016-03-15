<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectApiTest extends TestCase
{

    use DatabaseTransactions;

    public function testGetProjects()
    {
        $this->get('/project')
            ->seeStatusCode(200)
            ->seeJson();
    }

    public function testCreateProject()
    {
        $project = [
            'owner_id' => 1,
            'client_id' => 1,
            'name' => 'PHPUnit Project',
            'description' => 'PHPUnit Description',
            'progress' => 70,
            'status' => 'PHPUnit Status',
            'due_date' => '2020-01-01 00:00:00'
        ];
        $this->post('/project',$project)
            ->seeStatusCode(200)
            ->seeJson([
                'owner_id' => 1,
                'client_id' => 1,
                'name' => 'PHPUnit Project',
                'description' => 'PHPUnit Description',
                'progress' => 70,
                'status' => 'PHPUnit Status',
                'due_date' => '2020-01-01 00:00:00'
            ])
            ->see('"created_at":')
            ->see('"id":')
            ->seeInDatabase('projects',[
                'owner_id' => 1,
                'client_id' => 1,
                'name' => 'PHPUnit Project',
                'description' => 'PHPUnit Description',
                'progress' => 70,
                'status' => 'PHPUnit Status',
                'due_date' => '2020-01-01 00:00:00'
            ]);
    }

    public function testUpdateProject()
    {
        $project = [
            'owner_id' => 1,
            'client_id' => 1,
            'name' => 'PHPUnit Project',
            'description' => 'PHPUnit Description',
            'progress' => 70,
            'status' => 'PHPUnit Status',
            'due_date' => '2020-01-01 00:00:00'
        ];
        $project = \CodeProject\Entities\Project::create($project);
        $project->name = 'New PHPUnit Project Name';
        $this->put('/project/'.$project->id, $project->toArray())
            ->seeStatusCode(200)
            ->seeJson([
                'name' => 'New PHPUnit Project Name'
            ]);
    }

    public function testDeleteProject()
    {
        $project = [
            'owner_id' => 1,
            'client_id' => 1,
            'name' => 'PHPUnit Project',
            'description' => 'PHPUnit Description',
            'progress' => 70,
            'status' => 'PHPUnit Status',
            'due_date' => '2020-01-01 00:00:00'
        ];
        $project = \CodeProject\Entities\Project::create($project);
        $this->delete('/project/'.$project->id)
            ->seeStatusCode(200)
            ->seeJson([
                'success' => ['Excluído com sucesso']
            ]);
    }

    public function testGetNonExistentProject()
    {
        $this->get('/project/9123812931238123')
            ->seeStatusCode(404)
            ->seeJson();
    }

    public function testUpdateNonExistentProject()
    {
        $project = [
            'owner_id' => 1,
            'client_id' => 1,
            'name' => 'New PHPUnit Project Name',
            'description' => 'PHPUnit Description',
            'progress' => 70,
            'status' => 'PHPUnit Status',
            'due_date' => '2020-01-01 00:00:00'
        ];
        $this->put('/project/9123812931238123',$project)
            ->seeStatusCode(404)
            ->seeJson();
    }

    public function testDeleteNonExistentProject()
    {
        $this->delete('/project/9123812931238123')
            ->seeStatusCode(404)
            ->seeJson();
    }

    public function testInsertProjectWithoutRequiredFields()
    {
        $project = [];
        $this->post('/project',$project)
            ->seeStatusCode(400)
            ->seeJson([
                'error' => true,
            ]);
    }

    public function testInsertProjectWithoutValidFields()
    {
        $project = [
            'owner_id' => 1,
            'client_id' => 1,
            'name' => 'PHPUnit Project',
            'description' => 'PHPUnit Description',
            'progress' => 'as',
            'status' => 'PHPUnit Status',
            'due_date' => 'asdas'
        ];
        $this->post('/project',$project)
            ->seeStatusCode(400)
            ->seeJson([
                'error' => true,
            ])
            ->see('"due_date":[')
            ->see('valid')
            ->see('"progress"');
    }
}
