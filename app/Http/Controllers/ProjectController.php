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

    public function index()
    {
        return $this->service->index();
    }


    public function store(Request $request)
    {
        $result = $this->service->create($request->all());
        return response()->json($result,200);
    }

    public function show($id)
    {
        $result = $this->service->show($id);
        return response()->json($result,200);
    }

    public function update(Request $request, $id)
    {
        $result = $this->service->update($request->all(),$id);
        return response()->json($result,200);
    }

    public function destroy($id)
    {
        $result = $this->service->destroy($id);
        return response()->json($result,200);
    }
}
