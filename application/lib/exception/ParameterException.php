<?php


namespace app\lib\exception;

/*
 * 统一参数异常
 */
class ParameterException extends BaseException
{
    public $code = 400;
    public $msg = '参数异常';
    public $errorCode = 10000;
}