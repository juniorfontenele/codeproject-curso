<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use CodeProject\Services\ProjectTaskService;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;

class ProjectController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->repository->with(['owner','client'])->all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->service->create($request->all());
        return response()->json($result,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->service->show($id);
        return response()->json($result,200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = $this->service->update($request->all(),$id);
        return response()->json($result,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->service->destroy($id);
        return response()->json($result,200);
    }

    public function getTasks($project_id)
    {
        $project = $this->repository->with(['tasks'])->find($project_id);
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

    public function getMembers($project_id)
    {
        $project = $this->repository->with('members')->find($project_id);
        return response()->json($project->members,200);
    }

    public function addMember($project_id, $user_id)
    {
        return response()->json($this->service->addMember($user_id,$project_id),200);
    }

    public function removeMember($project_id, $user_id)
    {
        return response()->json($this->service->removeMember($user_id,$project_id),200);
    }

    public function isMember($project_id, $user_id)
    {
        if ($this->service->isMember($user_id,$project_id)) {
            $msg = ['is_member' => true];
        }
        else {
            $msg = ['is_member' => false];
        }
        return response()->json($msg,200);
    }
}
