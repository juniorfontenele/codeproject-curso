<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 07/03/2016
 * Time: 10:20
 */

namespace CodeProject\Validators;


use Prettus\Validator\LaravelValidator;

class ClientValidator extends LaravelValidator
{

    protected $rules = [
        'name' => 'required|max:255',
        'email' => 'required|email|max:255',
        'responsible' => 'required|max:255',
        'phone' => 'required'
    ];

    protected $messages = [
        'required' => 'O campo :attribute é obrigatório',
        'email' => 'O campo :attribute deve ser um e-mail válido',
        'max' => 'O campo :attribute deve conter :max caracteres no máximo'
    ];

}