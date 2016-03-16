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

    //Project Tasks

    public function testGetTask()
    {
        $this->get('/project/1/tasks')
            ->seeStatusCode(200)
            ->seeJson();
    }

    public function testGetTaskOnNonExistingProject()
    {
        $this->get('/project/9999999999/tasks')
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
        $this->post('/project/1/task',$task)
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
        $this->post('/project/99999999/task',$task)
            ->seeStatusCode(404)
            ->seeJson();
    }

    public function testCreateNewTaskWithoutRequiredFields()
    {
        $task = [];
        $this->post('/project/99999999/task',$task)
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
        $this->post('/project/99999999/task',$task)
            ->seeStatusCode(400)
            ->seeJson()
            ->see('Y-m-d')
            ->see('"start_date":')
            ->see('"due_date":');
    }

    //Project Members

    public function testAddProjectMember()
    {
        $this->post('/project/1/member/10')
            ->seeStatusCode(200)
            ->seeJson();
    }

    public function testRemoveProjectMember()
    {
        $this->delete('/project/1/member/1')
            ->seeStatusCode(200)
            ->seeJson();
    }

    public function testIsMemberProject()
    {
        $this->get('/project/1/member/1')
            ->seeStatusCode(200)
            ->seeJson();
    }

    public function testGetProjectMembers()
    {
        $this->get('/project/1/members')
            ->seeStatusCode(200)
            ->seeJson();
    }

    public function testAddMemberToNonExistingProject()
    {
        $this->post('/project/9999999999/member/1')
            ->seeStatusCode(404)
            ->seeJson()
            ->see('not_found');
    }

    public function testAddNonExistingMemberToProject()
    {
        $this->post('/project/1/member/999999999')
            ->seeStatusCode(404)
            ->seeJson()
            ->see('not_found');
    }

    public function testRemoveMemberOfNonExistingProject()
    {
        $this->delete('/project/9999999999/member/1')
            ->seeStatusCode(404)
            ->seeJson()
            ->see('not_found');
    }

    public function testRemoveNonExistingMemberOfProject()
    {
        $this->delete('/project/1/member/99999999')
            ->seeStatusCode(404)
            ->seeJson()
            ->see('not_found');
    }

    public function testGetMembersOfNonExistingProject()
    {
        $this->get('/project/999999999/members')
            ->seeStatusCode(404)
            ->seeJson()
            ->see('not_found');
    }
}
