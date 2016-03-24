<?php

namespace CodeProject\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class ProjectNoteValidator extends CodeProjectValidator {

    protected $rules = [
        'text' => 'required',
        'project_id' => 'required|numeric',
   ];

}