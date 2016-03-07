<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 07/03/2016
 * Time: 10:18
 */

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectService
{

    /**
     * @var ProjectValidator
     */
    protected $validator;

    /**
     * @var ProjectRepository
     */
    protected $repository;

    protected $error_message;
    protected $status_code;

    /**
     * ProjectService constructor.
     * @param ProjectRepository $repository
     * @param ProjectValidator $validator
     */
    public function __construct(ProjectRepository $repository, ProjectValidator $validator)
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
            $project = $this->repository->find($id);
            if (!$project) {
                $this->error_message = [
                    'error' => true,
                    'message' => [
                        'not_found' => [
                           'Projeto não encontrado'
                        ]
                    ]
                ];
                $this->status_code = 404;
                return false;
            }
            if ($project->update($data)) {
                return $project;
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
        $project = $this->repository->find($id);
        if (!$project) {
            $this->error_message = [
                'error' => true,
                'message' => [
                    'not_found' => ['Projeto não encontrado']
                ]
            ];
            $this->status_code = 404;
            return false;
        }
        if ($project->delete()) {
            return [
                'error' => false,
                'message' => [
                    'success' => ['Projeto excluído com sucesso']
                ]
            ];
        }
        else {
            $this->error_message = [
                'error' => true,
                'message' => [
                    'fail' => ['Falha ao excluir projeto']
                ]
            ];
            $this->status_code = 500;
            return false;
        }
    }

    public function show($id)
    {
        $project = $this->repository->find($id);
        if (!$project) {
            $this->error_message = [
                'error' => true,
                'message' => [
                    'not_found' => ['Projeto não encontrado']
                ]
            ];
            $this->status_code = 404;
            return false;
        }
        else {
            return $project;
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