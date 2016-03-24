<?php

namespace CodeProject\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class ProjectNote extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'text',
        'project_id',
    ];

    protected $casts = [
        'project_id' => 'integer',
    ];

    protected $table = 'project_notes';

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
