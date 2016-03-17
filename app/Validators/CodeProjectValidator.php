<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 15/03/2016
 * Time: 09:47
 */

namespace CodeProject\Validators;


use Prettus\Validator\LaravelValidator;

class CodeProjectValidator extends LaravelValidator
{
    protected $messages = [
        'required' => 'O campo :attribute é obrigatório',
        'email' => 'O campo :attribute deve ser um e-mail válido',
        'max' => 'O campo :attribute deve conter :max caracteres no máximo',
        'min' => 'O campo :attribute deve conter :max caracteres no mínimo',
        'between' => 'O campo :attribute deve estar entre :min e :max',
        'integer' => 'O campo :attribute deve conter um número inteiro',
        'date_format' => 'O campo :attribute deve ser no formato :format',
        'numeric' => 'O campo :attribute deve ser um número'
    ];
}