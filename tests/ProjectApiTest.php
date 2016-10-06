<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectApiTest extends TestCase
{

    use DatabaseTransactions;

    public function testGetProjects()
    {
        $this->get('/projects')
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
        $this->post('/projects',$project)
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
        $this->put('/projects/'.$project->id, $project->toArray())
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
        $this->delete('/projects/'.$project->id)
            ->seeStatusCode(200)
            ->seeJson([
                'success' => ['Excluído com sucesso']
            ]);
    }

    public function testGetNonExistentProject()
    {
        $this->get('/projects/9123812931238123')
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
        $this->put('/projects/9123812931238123',$project)
            ->seeStatusCode(404)
            ->seeJson();
    }

    public function testDeleteNonExistentProject()
    {
        $this->delete('/projects/9123812931238123')
            ->seeStatusCode(404)
            ->seeJson();
    }

    public function testInsertProjectWithoutRequiredFields()
    {
        $project = [];
        $this->post('/projects',$project)
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
        $this->post('/projects',$project)
            ->seeStatusCode(400)
            ->seeJson([
                'error' => true,
            ])
            ->see('"due_date":[')
            ->see('valid')
            ->see('"progress"');
    }

    //Project Tasks

    public function testGetTask()
    {
        $this->get('/projects/1/tasks')
            ->seeStatusCode(200)
            ->seeJson();
    }

    public function testGetTaskOnNonExistingProject()
    {
        $this->get('/projects/9999999999/tasks')
            ->seeStatusCode(404)
            ->seeJson();
    }

    public function testCreateNewTask()
    {
        $task = [
            'name' => 'PHPUnit Task',
            'start_date' => '2016-03-10',
            'due_date' => '2016-04-01',
            'status' => 1
        ];
        $this->post('/projects/1/tasks',$task)
            ->seeStatusCode(200)
            ->seeJson(['name' => 'PHPUnit Task', 'status' => 1]);
    }

    public function testCreateNewTaskOnNonExistingProject()
    {
        $task = [
            'name' => 'PHPUnit Task',
            'start_date' => '2016-03-10',
            'due_date' => '2016-04-01',
            'status' => 1
        ];
        $this->post('/projects/99999999/tasks',$task)
            ->seeStatusCode(404)
            ->seeJson();
    }

    public function testCreateNewTaskWithoutRequiredFields()
    {
        $task = [];
        $this->post('/projects/99999999/tasks',$task)
            ->seeStatusCode(400)
            ->seeJson()
            ->see('"name":')
            ->see('"start_date":')
            ->see('"due_date":')
            ->see('"status":');
    }

    public function testCreateNewTaskWithoutValidDate()
    {
        $task = [
            'name' => 'PHPUnit Task',
            'start_date' => '10 de Fev',
            'due_date' => '20 de Fev',
            'status' => 1
        ];
        $this->post('/projects/99999999/tasks',$task)
            ->seeStatusCode(400)
            ->seeJson()
            ->see('Y-m-d')
            ->see('"start_date":')
            ->see('"due_date":');
    }

    //Project Members

    public function testAddProjectMember()
    {
        $this->post('/projects/1/members/10')
            ->seeStatusCode(200)
            ->seeJson();
    }

    public function testRemoveProjectMember()
    {
        $this->delete('/projects/1/members/1')
            ->seeStatusCode(200)
            ->seeJson();
    }

    public function testIsMemberProject()
    {
        $this->get('/projects/1/members/1')
            ->seeStatusCode(200)
            ->seeJson();
    }

    public function testGetProjectMembers()
    {
        $this->get('/projects/1/members')
            ->seeStatusCode(200)
            ->seeJson();
    }

    public function testAddMemberToNonExistingProject()
    {
        $this->post('/projects/9999999999/members/1')
            ->seeStatusCode(404)
            ->seeJson()
            ->see('not_found');
    }

    public function testAddNonExistingMemberToProject()
    {
        $this->post('/projects/1/members/999999999')
            ->seeStatusCode(404)
            ->seeJson()
            ->see('not_found');
    }

    public function testRemoveMemberOfNonExistingProject()
    {
        $this->delete('/projects/9999999999/members/1')
            ->seeStatusCode(404)
            ->seeJson()
            ->see('not_found');
    }

    public function testRemoveNonExistingMemberOfProject()
    {
        $this->delete('/projects/1/members/99999999')
            ->seeStatusCode(404)
            ->seeJson()
            ->see('not_found');
    }

    public function testGetMembersOfNonExistingProject()
    {
        $this->get('/projects/999999999/members')
            ->seeStatusCode(404)
            ->seeJson()
            ->see('not_found');
    }
}
