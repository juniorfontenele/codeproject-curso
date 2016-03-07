<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClientApiTest extends TestCase
{

    use DatabaseTransactions;

    public function testGetClients()
    {
        $this->get('/client')
            ->seeStatusCode(200)
            ->seeJson();
    }

    public function testCreateClient()
    {
        $client = [
            'name' => 'PHPUnit Test Client',
            'address' => 'PHPUnit Test Address',
            'responsible' => 'PHPUnit Responsible',
            'email' => 'phpunit@phpunit.com',
            'phone' => '(00) 0000-0000',
            'obs' => 'PHPUnit OBS'
        ];
        $this->post('/client',$client)
            ->seeStatusCode(200)
            ->seeJson([
                'name' => 'PHPUnit Test Client',
                'address' => 'PHPUnit Test Address',
                'responsible' => 'PHPUnit Responsible',
                'email' => 'phpunit@phpunit.com',
                'phone' => '(00) 0000-0000',
                'obs' => 'PHPUnit OBS',
            ])
            ->see('"created_at":')
            ->see('"id":')
            ->seeInDatabase('clients',[
                'name' => 'PHPUnit Test Client',
                'address' => 'PHPUnit Test Address',
                'responsible' => 'PHPUnit Responsible',
                'email' => 'phpunit@phpunit.com',
                'phone' => '(00) 0000-0000',
                'obs' => 'PHPUnit OBS'
            ]);
    }

    public function testUpdateClient()
    {
        $client = [
            'name' => 'PHPUnit Test Client',
            'address' => 'PHPUnit Test Address',
            'responsible' => 'PHPUnit Responsible',
            'email' => 'phpunit@phpunit.com',
            'phone' => '(00) 0000-0000',
            'obs' => 'PHPUnit OBS'
        ];
        $client = \CodeProject\Entities\Client::create($client);
        $client->name = 'New PHPUnit Name';
        $this->put('/client/'.$client->id, $client->toArray())
            ->seeStatusCode(200)
            ->seeJson([
                'name' => 'New PHPUnit Name'
            ]);
    }

    public function testDeleteClient()
    {
        $client = [
            'name' => 'PHPUnit Test Client',
            'address' => 'PHPUnit Test Address',
            'responsible' => 'PHPUnit Responsible',
            'email' => 'phpunit@phpunit.com',
            'phone' => '(00) 0000-0000',
            'obs' => 'PHPUnit OBS'
        ];
        $client = \CodeProject\Entities\Client::create($client);
        $this->delete('/client/'.$client->id)
            ->seeStatusCode(200)
            ->seeJson([
                'success' => ['Usuário excluído com sucesso']
            ]);
    }

    public function testGetNonExistentClient()
    {
        $this->get('/client/9123812931238123')
            ->seeStatusCode(404)
            ->seeJson([
               'not_found' => ['Usuário não encontrado']
            ]);
    }

    public function testUpdateNonExistentClient()
    {
        $client = [
            'name' => 'New PHPUnit Test Client',
            'address' => 'New PHPUnit Test Address',
            'responsible' => 'New PHPUnit Responsible',
            'email' => 'newphpunit@phpunit.com',
            'phone' => '(00) 0000-0000',
            'obs' => 'New PHPUnit OBS'
        ];
        $this->put('/client/9123812931238123',$client)
            ->seeStatusCode(404)
            ->seeJson([
                'not_found' => ['Usuário não encontrado']
            ]);
    }

    public function testDeleteNonExistentClient()
    {
        $this->delete('/client/9123812931238123')
            ->seeStatusCode(404)
            ->seeJson([
                'not_found' => ['Usuário não encontrado']
            ]);
    }

    public function testInsertClientWithoutRequiredFields()
    {
        $client = [];
        $this->post('/client',$client)
            ->seeStatusCode(500)
            ->seeJson([
                'error' => true,
            ]);
    }

    public function testInsertClientWithoutValidFields()
    {
        $client = [
            'name' => 'New PHPUnit Test Client',
            'address' => 'New PHPUnit Test Address',
            'responsible' => 'New PHPUnit Responsible',
            'email' => 'newphpunit@phpunit',
            'phone' => '(00) 0000-0000',
            'obs' => 'New PHPUnit OBS'
        ];
        $this->post('/client',$client)
            ->seeStatusCode(500)
            ->seeJson([
                'error' => true,
            ])
            ->see('"email":[')
            ->see('valid');
    }
}
