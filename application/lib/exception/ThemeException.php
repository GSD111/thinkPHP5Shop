<?php


namespace app\lib\exception;

/*
 * 主题异常
 */
class ThemeException extends BaseException
{
    public $code = 404;
    public $msg = '请求的主题主键ID不存在';
    public $errorCode = 30000;
}