<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;

class ProjectTaskController extends Controller
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

    public function getTasks($project_id)
    {
        $project = $this->repository->skipPresenter()->with(['tasks'])->find($project_id);
        return response()->json($project->tasks,200);
    }

    public function addTask(Request $request,$project_id)
    {
        return response()->json($this->service->addTask($request->all(),$project_id),200);
    }

    public function removeTask($project_id,$task_id)
    {
        return response()->json($this->service->removeTask($task_id,$project_id),200);
    }

    public function showTask($project_id, $task_id)
    {
        return response()->json($this->service->showTask($task_id,$project_id),200);
    }

    public function updateTask(Request $request, $project_id, $task_id)
    {
        return response()->json($this->service->updateTask($request->all(),$task_id,$project_id),200);
    }
}
