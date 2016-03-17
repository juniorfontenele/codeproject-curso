<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 07/03/2016
 * Time: 10:20
 */

namespace CodeProject\Validators;


class ProjectValidator extends CodeProjectValidator
{

    protected $rules = [
        'name' => 'required|max:255',
        'owner_id' => 'required|integer',
        'client_id' => 'required|integer',
        'due_date' => 'required|date|after:now',
        'progress' => 'required|integer|between:0,100',
        'status' => 'required|max:255',
        'description' => 'max:255'
    ];

}