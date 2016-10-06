<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;

class ProjectNoteController extends Controller
{
    /**
     * @var ProjectRepository
     */
    protected $repository;

    /**
     * @var ProjectService
     */
    protected $service;

    /**
     * ProjectController constructor.
     * @param ProjectRepository $repository
     * @param ProjectService $service
     */
    public function __construct(ProjectRepository $repository, ProjectService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

	public function getNotes($project_id)
	{
		return response()->json($this->service->getNotes($project_id),200);
	}

	public function addNote(Request $request,$project_id)
	{
		return response()->json($this->service->addNote($request->all(),$project_id),200);
	}

	public function removeNote($project_id,$note_id)
	{
		return response()->json($this->service->removeNote($note_id,$project_id),200);
	}

	public function showNote($project_id, $note_id)
	{
		return response()->json($this->service->showNote($note_id,$project_id),200);
	}

	public function updateNote(Request $request, $project_id, $note_id)
	{
		return response()->json($this->service->updateNote($request->all(),$note_id,$project_id),200);
	}
}
