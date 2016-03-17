<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 07/03/2016
 * Time: 10:18
 */

namespace CodeProject\Services;


use CodeProject\Exceptions\CodeProjectException;
use CodeProject\Repositories\ClientRepository;
use CodeProject\Validators\ClientValidator;
use Illuminate\Support\MessageBag;

class ClientService
{

    /**
     * @var ClientValidator
     */
    protected $validator;

    /**
     * @var ClientRepository
     */
    protected $repository;

    /**
     * ClientService constructor.
     * @param ClientRepository $repository
     * @param ClientValidator $validator
     */
    public function __construct(ClientRepository $repository, ClientValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function create(array $data)
    {
        $this->validator->with($data)->passesOrFail();
        return $this->repository->create($data);
    }

    public function update(array $data, $id)
    {
        $this->validator->with($data)->passesOrFail();
        $client = $this->repository->find($id);
        if (!$client->update($data)) {
            throw new CodeProjectException(new MessageBag(['fail' => 'Falha ao atualizar']),500);
        }
        return $client;
    }

    public function destroy($id)
    {
        $client = $this->repository->find($id);
        if (!$client->delete()) {
            throw new CodeProjectException(new MessageBag(['fail' => 'Falha ao excluir']),500);
        }
        return [
            'error' => false,
            'message' => [
                'success' => ['Excluído com sucesso']
            ]
        ];
    }

    public function show($id)
    {
        $client = $this->repository->find($id);
        return $client;
    }
}