<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\Project;
use League\Fractal\TransformerAbstract;
use CodeProject\Entities\User;

/**
 * Class UserTransformer
 * @package namespace CodeProject\Transformers;
 */
class UserTransformer extends TransformerAbstract
{

	/**
	 * Transform the \User entity
	 * @param User|\User $model
	 * @return array
	 */
    public function transform(User $model)
    {
        return [
            'id'         => (int) $model->id,
	        'name' => $model->name,
	        'email' => $model->email,
	        'projects' => $model->projects,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }

	public function includeProjects(Project $model)
	{
		return $this->collection($model->projects, new ProjectTransformer());
	}
}
