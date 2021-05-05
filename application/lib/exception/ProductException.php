<?php


namespace app\lib\exception;


class ProductException extends BaseException
{
    public $code = 404;
    public $msg = '指定的产品信息不存在，请检查参数';
    public $errorCode = 20000;
}