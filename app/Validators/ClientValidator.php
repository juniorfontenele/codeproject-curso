<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 07/03/2016
 * Time: 10:20
 */

namespace CodeProject\Validators;


class ClientValidator extends CodeProjectValidator
{

    protected $rules = [
        'name' => 'required|max:255',
        'email' => 'required|email|max:255',
        'responsible' => 'required|max:255',
        'phone' => 'required'
    ];

}