<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 07/03/2016
 * Time: 10:18
 */

namespace CodeProject\Services;


use CodeProject\Exceptions\CodeProjectException;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;
use Illuminate\Support\MessageBag;

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
        $this->validator->with($data)->passesOrFail();
        return $this->repository->create($data);
    }

    public function update(array $data, $id)
    {
        $this->validator->with($data)->passesOrFail();
        $project = $this->repository->find($id);
        if (!$project->update($data)) {
            throw new CodeProjectException(new MessageBag(['fail' => 'Falha ao atualizar']),500);
        }
        return $project;
    }

    public function destroy($id)
    {
        $project = $this->repository->find($id);
        if (!$project->delete()) {
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
        $project = $this->repository->find($id);
        return $project;
    }
}