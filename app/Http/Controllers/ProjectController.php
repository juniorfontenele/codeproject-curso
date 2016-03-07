<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
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
        if ($result) {
            return response()->json($result,200);
        }
        else {
            return response()->json($this->service->getErrorMessage(),$this->service->getStatusCode());
        }
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
        if ($result) {
            return response()->json($result,200);
        }
        else {
            return response()->json($this->service->getErrorMessage(),$this->service->getStatusCode());
        }
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
        if ($result) {
            return response()->json($result,200);
        }
        else {
            return response()->json($this->service->getErrorMessage(),$this->service->getStatusCode());
        }
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
        if ($result) {
            return response()->json($result,200);
        }
        else {
            return response()->json($this->service->getErrorMessage(),$this->service->getStatusCode());
        }
    }
}
