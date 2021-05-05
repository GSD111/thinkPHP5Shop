<?php


namespace app\lib\exception;


class ThemeException extends BaseException
{
    public $code = 404;
    public $msg = '请求的主题主键ID不存在';
    public $errorCode = 30000;
}