<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;

class ProjectMemberController extends Controller
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

	public function getMembers($project_id)
	{
		$project = $this->repository->skipPresenter()->with('members')->find($project_id);
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
