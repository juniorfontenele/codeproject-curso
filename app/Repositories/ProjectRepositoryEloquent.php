<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 29/02/2016
 * Time: 18:21
 */

namespace CodeProject\Repositories;


use CodeProject\Entities\Project;

class ProjectRepositoryEloquent extends BaseRepository implements ProjectRepository
{

	public function model()
	{
		return Project::class;
	}

} 