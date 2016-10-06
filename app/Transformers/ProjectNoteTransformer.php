<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\Project;
use League\Fractal\TransformerAbstract;
use CodeProject\Entities\ProjectNote;

/**
 * Class ProjectNoteTransformer
 * @package namespace CodeProject\Transformers;
 */
class ProjectNoteTransformer extends TransformerAbstract
{

	/**
	 * Transform the \ProjectNote entity
	 * @param ProjectNote|\ProjectNote $model
	 * @return array
	 */
    public function transform(ProjectNote $model)
    {
        return [
            'id'         => (int) $model->id,
			'text' => $model->text,
	        'project_id' => $model->project_id,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }

	public function includeProject(Project $model)
	{
		return $this->item($model->project, new ProjectTransformer());
	}
}
