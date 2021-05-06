<?php


namespace app\lib\exception;

/*
 * Banner异常
 */
class BannerMissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的Banner信息不存在';
    public $errorCode = 40000;
}