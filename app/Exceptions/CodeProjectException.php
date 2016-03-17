<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 15/03/2016
 * Time: 10:44
 */

namespace CodeProject\Exceptions;


use Illuminate\Contracts\Support\MessageBag;

class CodeProjectException extends \Exception
{

    protected $messageBag;

    /**
     * CodeProjectException constructor.
     * @param MessageBag $messageBag
     * @param int $statusCode
     */
    public function __construct(MessageBag $messageBag, $statusCode = 400)
    {
        $this->messageBag = $messageBag;
        $this->code = $statusCode;
    }


    /**
     * @return MessageBag
     */
    public function getMessageBag(){
        return $this->messageBag;
    }
}