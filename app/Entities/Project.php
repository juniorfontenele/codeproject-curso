<?php

namespace CodeProject\Entities;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'owner_id',
        'client_id',
        'name',
        'description',
        'progress',
        'status',
        'due_date'
    ];

    protected $dates = ['due_date'];

    protected $casts = [
        'owner_id' => 'integer',
        'client_id' => 'integer',
        'progress' => 'integer'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class,'project_members','project_id','user_id');
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTask::class);
    }
}
