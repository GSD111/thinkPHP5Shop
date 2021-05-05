<?php


namespace app\lib\exception;


class CategoryException extends BaseException
{
    public $code = 404;
    public $msg = '请求的分类信息不存在，请检查参数';
    public $errorCode = 50000;
}