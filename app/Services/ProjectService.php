<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 07/03/2016
 * Time: 10:18
 */

namespace CodeProject\Services;


use CodeProject\Exceptions\CodeProjectException;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Repositories\ProjectTaskRepository;
use CodeProject\Repositories\UserRepository;
use CodeProject\Validators\ProjectTaskValidator;
use CodeProject\Validators\ProjectValidator;
use Illuminate\Support\MessageBag;

class ProjectService
{

    /**
     * @var ProjectValidator
     */
    protected $validator;

    /**
     * @var ProjectTaskValidator
     */
    protected $taskValidator;

    /**
     * @var ProjectRepository
     */
    protected $repository;

    /**
     * @var ProjectTaskRepository
     */
    protected $taskRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * ProjectService constructor.
     * @param ProjectRepository $repository
     * @param ProjectValidator $validator
     * @param ProjectTaskValidator $taskValidator
     * @param ProjectTaskRepository $taskRepository
     * @param UserRepository $userRepository
     */
    public function __construct(ProjectRepository $repository, ProjectValidator $validator, ProjectTaskValidator $taskValidator, ProjectTaskRepository $taskRepository, UserRepository $userRepository)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->taskValidator = $taskValidator;
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
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

    public function addTask(array $data, $project_id)
    {
        $data['project_id'] = $project_id;
        $this->taskValidator->with($data)->passesOrFail();
        $project = $this->repository->find($project_id);
        $task = $project->tasks()->create($data);
        if (!$task) {
            throw new CodeProjectException(new MessageBag(['fail' => 'Falha ao criar']),500);
        }
        return $task;
    }

    public function removeTask($task_id, $project_id)
    {
        $project = $this->repository->with('tasks')->find($project_id);
        $task = $project->tasks()->find($task_id);
        if ($task == NULL) {
            throw new CodeProjectException(new MessageBag(['not_found' => 'Tarefa não encontrada no projeto']),404);
        }
        if (!$this->taskRepository->delete($task_id)) {
            throw new CodeProjectException(new MessageBag(['fail' => 'Falha ao excluir']),500);
        }
        return [
            'error' => false,
            'message' => [
                'success' => ['Excluído com sucesso']
            ]
        ];
    }

    public function showTask($task_id, $project_id)
    {
        $project = $this->repository->with('tasks')->find($project_id);
        $task = $project->tasks()->find($task_id);
        if ($task == NULL) {
            throw new CodeProjectException(new MessageBag(['not_found' => 'Tarefa não encontrada no projeto'],404));
        }
        return $task;
    }

    public function addMember($user_id, $project_id)
    {
        $project = $this->repository->with('members')->find($project_id);
        $this->userRepository->find($user_id);
        if ($project->members()->find($user_id) != NULL) {
            throw new CodeProjectException(new MessageBag(['fail' => 'Usuário já é membro do projeto']),400);
        }
        $project->members()->attach($user_id);
        return [
            'error' => false,
            'message' => [
                'success' => ['Membro associado com sucesso']
            ]
        ];
    }

    public function removeMember($user_id, $project_id)
    {
        $project = $this->repository->with('members')->find($project_id);
        $this->userRepository->find($user_id);
        if ($project->members()->find($user_id) == NULL) {
            throw new CodeProjectException(new MessageBag(['not_found' => 'Usuário não é membro do projeto']),404);
        }
        $project->members()->detach($user_id);
        return [
            'error' => false,
            'message' => [
                'success' => ['Membro removido com sucesso']
            ]
        ];
    }

    public function isMember($user_id, $project_id)
    {
        $project = $this->repository->with('members')->find($project_id);
        if ($project->members()->find($user_id) == NULL) {
            return false;
        }
        else {
            return true;
        }
    }

}