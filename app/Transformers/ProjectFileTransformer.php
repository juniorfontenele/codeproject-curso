<?php

namespace CodeProject\Transformers;

use League\Fractal\TransformerAbstract;
use CodeProject\Entities\ProjectFile;

/**
 * Class ProjectFileTransformer
 * @package namespace CodeProject\Transformers;
 */
class ProjectFileTransformer extends TransformerAbstract
{

	/**
	 * Transform the \ProjectFile entity
	 * @param ProjectFile|\ProjectFile $model
	 * @return array
	 */
    public function transform(ProjectFile $model)
    {
        return [
            'id'         => (int) $model->id,
			'name' => $model->name,
	        'description' => $model->description,
	        'size' => $model->size,
	        'extension' => $model->extension,
	        'mime_type' => $model->mime_type,
	        'save_name' => $model->save_name,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
