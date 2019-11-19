<?php
/**
 * Created by PhpStorm.
 * User: Liliaze
 * Date: 12/11/2019
 * Time: 13:00
 */


class UnknownException extends \Exception
{
    private $HttpCode = 520;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getHttpCode (){
        return $this->HttpCode;
    }
}