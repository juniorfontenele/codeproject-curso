<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 07/03/2016
 * Time: 10:18
 */

namespace CodeProject\Services;


use CodeProject\Repositories\ClientRepository;
use CodeProject\Validators\ClientValidator;
use Prettus\Validator\Exceptions\ValidatorException;

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

    protected $error_message;
    protected $status_code;

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
        try {
            $this->validator->with($data)->passesOrFail();
            return $this->repository->create($data);
        } catch (ValidatorException $e) {
            $this->error_message = [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
            $this->status_code = 500;
            return false;
        }
    }

    public function update(array $data, $id)
    {
        try {
            $this->validator->with($data)->passesOrFail();
            $client = $this->repository->find($id);
            if (!$client) {
                $this->error_message = [
                    'error' => true,
                    'message' => [
                        'not_found' => [
                           'Usuário não encontrado'
                        ]
                    ]
                ];
                $this->status_code = 404;
                return false;
            }
            if ($client->update($data)) {
                return $client;
            }
            else {
                $this->error_message = [
                    'error' => true,
                    'message' => [
                        'fail' => [
                            'Falha ao atualizar'
                        ]
                    ]
                ];
                $this->status_code = 500;
                return false;
            }
        } catch (ValidatorException $e) {
            $this->error_message = [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
            $this->status_code = 500;
            return false;
        }
    }

    public function destroy($id)
    {
        $client = $this->repository->find($id);
        if (!$client) {
            $this->error_message = [
                'error' => true,
                'message' => [
                    'not_found' => ['Usuário não encontrado']
                ]
            ];
            $this->status_code = 404;
            return false;
        }
        if ($client->delete()) {
            return [
                'error' => false,
                'message' => [
                    'success' => ['Usuário excluído com sucesso']
                ]
            ];
        }
        else {
            $this->error_message = [
                'error' => true,
                'message' => [
                    'fail' => ['Falha ao excluir usuário']
                ]
            ];
            $this->status_code = 500;
            return false;
        }
    }

    public function show($id)
    {
        $client = $this->repository->find($id);
        if (!$client) {
            $this->error_message = [
                'error' => true,
                'message' => [
                    'not_found' => ['Usuário não encontrado']
                ]
            ];
            $this->status_code = 404;
            return false;
        }
        else {
            return $client;
        }
    }

    public function getErrorMessage()
    {
        return $this->error_message;
    }

    public function getStatusCode()
    {
        return $this->status_code;
    }
}