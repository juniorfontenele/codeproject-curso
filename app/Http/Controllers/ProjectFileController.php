<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;

class ProjectFileController extends Controller
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

	public function getFiles($project_id)
	{
		return response()->json($this->service->getFiles($project_id),200);
	}

	public function addFile(Request $request,$project_id)
	{
		$file = $request->file('file');
		$extension = $file->getClientOriginalExtension();

		$data = [
			'file' => $file,
			'extension' => $extension,
			'name' => $request->name,
			'description' => $request->description,
			'save_name' => $project_id."_".sha1($file->getClientOriginalName().$file->getSize()),
			'project_id' => $project_id,
			'size' => $file->getSize(),
			'mime_type' => $file->getMimeType()
		];
		
		return response()->json($this->service->createFile($data),200);
	}

	public function removeFile($project_id,$file_id)
	{
		return response()->json($this->service->removeFile($file_id,$project_id),200);
	}

	public function showFile($project_id, $file_id)
	{
		return response()->json($this->service->showFile($file_id,$project_id),200);
	}

	public function updateFile(Request $request, $project_id, $file_id)
	{
		dd($request->all());
		$file = $request->file('file');
		$extension = $file->getClientOriginalExtension();

		$data = [
			'file' => $file,
			'extension' => $extension,
			'name' => $request->name,
			'description' => $request->description,
			'save_name' => $project_id."_".sha1($file->getClientOriginalName().$file->getSize()),
			'project_id' => $project_id,
			'size' => $file->getSize()
		];

		return response()->json($this->service->updateFile($data,$file_id,$project_id),200);
	}
}
