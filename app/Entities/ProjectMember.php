<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 17/03/2016
 * Time: 14:22
 */

namespace CodeProject\Entities;


use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class ProjectMember extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'project_id',
        'user_id'
    ];
}