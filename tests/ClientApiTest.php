<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClientApiTest extends TestCase
{

    use DatabaseTransactions;

    public function testGetClients()
    {
        $this->get('/clients')
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
        $this->post('/clients',$client)
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
        $this->put('/clients/'.$client->id, $client->toArray())
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
        $this->delete('/clients/'.$client->id)
            ->seeStatusCode(200)
            ->seeJson();
    }

    public function testGetNonExistentClient()
    {
        $this->get('/clients/9123812931238123')
            ->seeStatusCode(404)
            ->seeJson();
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
        $this->put('/clients/9123812931238123',$client)
            ->seeStatusCode(404)
            ->seeJson();
    }

    public function testDeleteNonExistentClient()
    {
        $this->delete('/clients/9123812931238123')
            ->seeStatusCode(404)
            ->seeJson();
    }

    public function testInsertClientWithoutRequiredFields()
    {
        $client = [];
        $this->post('/clients',$client)
            ->seeStatusCode(400)
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
        $this->post('/clients',$client)
            ->seeStatusCode(400)
            ->seeJson([
                'error' => true,
            ])
            ->see('"email":[')
            ->see('valid');
    }
}
