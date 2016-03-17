<?php

namespace CodeProject\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class ProjectTaskValidator extends CodeProjectValidator {

    protected $rules = [
        'name' => 'required|max:255',
        'project_id' => 'required|numeric',
        'start_date' => 'required|date_format:Y-m-d',
        'due_date' => 'required|date_format:Y-m-d',
        'status' => 'required|numeric'
   ];

}