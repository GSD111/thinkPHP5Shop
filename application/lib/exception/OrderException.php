<?php


namespace app\lib\exception;


class OrderException extends BaseException
{
    public $code = 404;
    public $msg = '订单商品信息有误,商品Id不存在';
    public $errorCode = 80000;
}