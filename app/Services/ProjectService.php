<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 07/03/2016
 * Time: 10:18
 */

namespace CodeProject\Services;


use CodeProject\Exceptions\CodeProjectException;
use CodeProject\Repositories\ProjectFileRepository;
use CodeProject\Repositories\ProjectNoteRepository;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Repositories\ProjectTaskRepository;
use CodeProject\Repositories\UserRepository;
use CodeProject\Validators\ProjectFileValidator;
use CodeProject\Validators\ProjectNoteValidator;
use CodeProject\Validators\ProjectTaskValidator;
use CodeProject\Validators\ProjectValidator;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Filesystem\Filesystem as File;
use Illuminate\Support\MessageBag;
use LucaDegasperi\OAuth2Server\Authorizer;

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
     * @var Authorizer
     */
    protected $authorizer;
    /**
     * @var ProjectNoteRepository
     */
    protected $noteRepository;
    /**
     * @var ProjectNoteValidator
     */
    protected $noteValidator;
	/**
	 * @var File
	 */
	private $file;
	/**
	 * @var Storage
	 */
	private $storage;
	/**
	 * @var ProjectTaskValidator
	 */
	private $projectTaskValidator;
	/**
	 * @var ProjectFileValidator
	 */
	private $projectFileValidator;
	/**
	 * @var ProjectFileRepository
	 */
	private $projectFileRepository;

	/**
	 * ProjectService constructor.
	 * @param ProjectRepository $repository
	 * @param ProjectValidator $validator
	 * @param ProjectTaskValidator $taskValidator
	 * @param ProjectTaskRepository $taskRepository
	 * @param UserRepository $userRepository
	 * @param ProjectNoteRepository $projectNote
	 * @param ProjectNoteValidator $projectNoteValidator
	 * @param Authorizer $authorizer
	 * @param File $file
	 * @param Storage $storage
	 * @param ProjectFileValidator $projectFileValidator
	 * @param ProjectFileRepository $projectFileRepository
	 */
    public function __construct(ProjectRepository $repository, ProjectValidator $validator, ProjectTaskValidator $taskValidator, ProjectTaskRepository $taskRepository, UserRepository $userRepository, ProjectNoteRepository $projectNote, ProjectNoteValidator $projectNoteValidator, Authorizer $authorizer, File $file, Storage $storage, ProjectFileValidator $projectFileValidator, ProjectFileRepository $projectFileRepository)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->taskValidator = $taskValidator;
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
        $this->authorizer = $authorizer;
        $this->noteRepository = $projectNote;
        $this->noteValidator = $projectNoteValidator;
	    $this->file = $file;
	    $this->storage = $storage;
	    $this->projectFileValidator = $projectFileValidator;
	    $this->projectFileRepository = $projectFileRepository;
    }

    public function index()
    {
        $user_id = $this->authorizer->getResourceOwnerId();
	    $projects = $this->repository->findWhere(['owner_id' => $user_id]);
        return $projects;
    }

    public function create(array $data)
    {
        $data['owner_id'] = $this->authorizer->getResourceOwnerId();
        $this->validator->with($data)->passesOrFail();
        $project = $this->repository->create($data);
        if (!$project) {
            throw new CodeProjectException(new MessageBag(['fail' => 'Falha ao criar']),500);
        }
        $project->members()->attach($data['owner_id']);
        return $project;
    }

    public function update(array $data, $id)
    {
        $data['owner_id'] = $this->authorizer->getResourceOwnerId();
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
        $project->members()->detach();
        $project->tasks()->delete();
        return [
            'error' => false,
            'message' => [
                'success' => ['Excluído com sucesso']
            ]
        ];
    }

    public function show($id)
    {
        $project = $this->repository->with(['client','owner','members'])->find($id);
        return $project;
    }

    public function addTask(array $data, $project_id)
    {
        $data['project_id'] = $project_id;
        $this->taskValidator->with($data)->passesOrFail();
        $task = $this->taskRepository->create($data);
        if (!$task) {
            throw new CodeProjectException(new MessageBag(['fail' => 'Falha ao criar']),500);
        }
        return $task;
    }

    public function removeTask($task_id, $project_id)
    {
	    $task = $this->taskRepository->findWhere(['id' => $task_id, 'project_id' => $project_id]);
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
        $project = $this->repository->skipPresenter()->with('tasks')->find($project_id);
        $task = $project->tasks()->find($task_id);
        if ($task == NULL) {
            throw new CodeProjectException(new MessageBag(['not_found' => 'Tarefa não encontrada no projeto'],404));
        }
        return $task;
    }

    public function updateTask(array $data, $task_id, $project_id)
    {
        $data['project_id'] = $project_id;
        $this->taskValidator->with($data)->passesOrFail();
        $task = $this->taskRepository->findWhere(['id' => $task_id, 'project_id' => $project_id]);
        if ($task == NULL) {
            throw new CodeProjectException(new MessageBag(['not_found' => 'Tarefa não encontrada no projeto']),404);
        }
	    $task = $this->taskRepository->update($data, $task_id);
        if (!$task) {
            throw new CodeProjectException(new MessageBag(['fail' => 'Falha ao atualizar']),500);
        }
        return $task;
    }

    public function addMember($user_id, $project_id)
    {
	    $project = $this->repository->skipPresenter()->with('members')->find($project_id);
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
        $project = $this->repository->skipPresenter()->with('members')->find($project_id);
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
        $project = $this->repository->skipPresenter()->with('members')->find($project_id);
        if ($project->members()->find($user_id) == NULL) {
            return false;
        }
        else {
            return true;
        }
    }

    public function isOwner($user_id, $project_id)
    {
        $project = $this->repository->skipPresenter()->find($project_id);
        if ($project->owner_id == $user_id) {
            return true;
        }
        else {
            return false;
        }
    }

    public function addNote(array $data, $project_id)
    {
        $data['project_id'] = $project_id;
        $this->noteValidator->with($data)->passesOrFail();
        $note = $this->noteRepository->create($data);
        if (!$note) {
            throw new CodeProjectException(new MessageBag(['fail' => 'Falha ao criar']),500);
        }
        return $note;
    }

    public function removeNote($note_id, $project_id)
    {
        $note = $this->noteRepository->findWhere(['id' => $note_id, 'project_id' => $project_id]);
        if ($note == NULL) {
            throw new CodeProjectException(new MessageBag(['not_found' => 'Nota não encontrada no projeto']),404);
        }
        if (!$this->noteRepository->delete($note_id)) {
            throw new CodeProjectException(new MessageBag(['fail' => 'Falha ao excluir']),500);
        }
        return [
            'error' => false,
            'message' => [
                'success' => ['Excluído com sucesso']
            ]
        ];
    }

    public function showNote($note_id, $project_id)
    {
        $note = $this->noteRepository->findWhere(['id' => $note_id, 'project_id' => $project_id]);
        if ($note == NULL) {
            throw new CodeProjectException(new MessageBag(['not_found' => 'Nota não encontrada no projeto'],404));
        }
        return $note;
    }

    public function updateNote(array $data, $note_id, $project_id)
    {
        $data['project_id'] = $project_id;
        $this->noteValidator->with($data)->passesOrFail();
        $note = $this->noteRepository->findWhere(['id' => $note_id, 'project_id' => $project_id]);
        if ($note == NULL) {
            throw new CodeProjectException(new MessageBag(['not_found' => 'Nota não encontrada no projeto']),404);
        }
	    $note = $this->noteRepository->update($data, $note_id);
        if (!$note) {
            throw new CodeProjectException(new MessageBag(['fail' => 'Falha ao atualizar']),500);
        }
        return $note;
    }

	public function getNotes($project_id)
	{
		return $this->noteRepository->findWhere(['project_id' => $project_id]);
	}

	public function createFile(array $data)
	{
		$data['extension'] = strtolower($data['extension']);
		$this->projectFileValidator->with($data)->passesOrFail();
		$project = $this->repository->skipPresenter()->find($data['project_id']);
		if (!$project) {
			throw new CodeProjectException(new MessageBag(['not_found' => 'Projeto não encontrado']),404);
		}

		$file = $project->files()->where(['save_name' => $data['save_name']])->first();
		if ($file) {
			throw new CodeProjectException(new MessageBag(['fail' => 'Arquivo existente']),500);
		}

		if (!$this->storage->disk()->put($data['save_name'], $this->file->get($data['file']))) {
			throw new CodeProjectException(new MessageBag(['fail' => 'Falha ao gravar arquivo']),500);
		}

		if (!$project->files()->create($data)) {
			throw new CodeProjectException(new MessageBag(['fail' => 'Falha ao salvar informações do arquivo']),500);
		}

		return [
			'error' => false,
			'message' => [
				'success' => ['Arquivo gravado com sucesso']
			]
		];
	}

	public function getFiles($project_id)
	{
		return $this->projectFileRepository->findWhere(['project_id' => $project_id]);
	}

	public function removeFile($file_id, $project_id)
	{
		$project = $this->repository->skipPresenter()->find($project_id);
		$file = $project->files()->where(['id' => $file_id])->first();
		if (!$file) {
			throw new CodeProjectException(new MessageBag(['not_found' => 'Arquivo não encontrado no projeto']),404);
		}
		if (!$this->storage->disk()->delete($file->save_name)) {
			throw new CodeProjectException(new MessageBag(['fail' => 'Falha ao excluir']),500);
		}
		if (!$this->projectFileRepository->delete($file_id)) {
			throw new CodeProjectException(new MessageBag(['fail' => 'Falha ao excluir']),500);
		}
		return [
			'error' => false,
			'message' => [
				'success' => ['Excluído com sucesso']
			]
		];
	}

	public function showFile($file_id, $project_id)
	{
		$file = $this->projectFileRepository->findWhere(['id' => $file_id, 'project_id' => $project_id]);
		if (!$file) {
			throw new CodeProjectException(new MessageBag(['not_found' => 'Arquivo não encontrado no projeto']),404);
		}
		return $file;
	}

	public function updateFile(array $data, $file_id, $project_id)
	{
		$data['project_id'] = $project_id;
		$this->projectFileValidator->with($data)->passesOrFail();
		$file = $this->projectFileRepository->findWhere(['id' => $file_id, 'project_id' => $project_id]);
		if (!$file) {
			throw new CodeProjectException(new MessageBag(['not_found' => 'Arquivo não encontrado no projeto']),404);
		}
		if (!$this->storage->disk()->delete($file['save_name'])) {
			throw new CodeProjectException(new MessageBag(['fail' => 'Falha ao excluir']),500);
		}
		$file = $this->projectFileRepository->update($data, $file_id);
		if (!$file) {
			throw new CodeProjectException(new MessageBag(['fail' => 'Falha ao atualizar']),500);
		}
		return $file;
	}

}