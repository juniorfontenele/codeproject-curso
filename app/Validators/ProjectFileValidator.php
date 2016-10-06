<?php

namespace CodeProject\Validators;

class ProjectFileValidator extends CodeProjectValidator {

    protected $rules = [
	    'name' => 'required|max:255',
	    'extension' => 'required|max:3',
	    'project_id' => 'required|integer',
	    'description' => 'required',
	    'save_name' => 'required',
	    'size' => 'required|integer',
   ];

}