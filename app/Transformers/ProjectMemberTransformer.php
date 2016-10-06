<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\User;
use League\Fractal\TransformerAbstract;

/**
 * Class ProjectMemberTransformer
 * @package namespace CodeProject\Transformers;
 */
class ProjectMemberTransformer extends TransformerAbstract
{

	/**
	 * Transform the \User entity
	 * @param User| $model
	 * @return array
	 */
    public function transform(User $model)
    {
        return (int) $model->id;
    }
}
