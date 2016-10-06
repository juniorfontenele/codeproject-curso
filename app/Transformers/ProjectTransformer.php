<?php

namespace CodeProject\Transformers;

use League\Fractal\TransformerAbstract;
use CodeProject\Entities\Project;

/**
 * Class ProjectTransformer
 * @package namespace CodeProject\Transformers;
 */
class ProjectTransformer extends TransformerAbstract
{

	protected $defaultIncludes = ['members'];

    /**
     * Transform the \Project entity
     * @param Project|\Project $model
     * @return array
     */
    public function transform(Project $model)
    {
        return [
            'id' => (int)$model->id,
            'owner_id' => (int)$model->owner_id,
            'client_id' => (int)$model->client_id,
	        'members' => $model->members,
            'name' => $model->name,
            'description' => $model->description,
            'progress' => (int)$model->progress,
            'status' => $model->status,
            'due_date' => $model->due_date->format('Y-m-d'),
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }

	public function includeMembers(Project $model)
	{
		return $this->collection($model->members, new ProjectMemberTransformer());
	}
}
