<?php


namespace app\lib\exception;


use think\Exception;
use think\exception\Handle;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;

    //返回客户端当前需要的URL路径

    public function render(Exception $e)
    {
        if ($e instanceof BaseException) {
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        } else {
            $this->code = 500;
            $this->msg = '服务器内部错误';
            $this->errorCode = 999;
        }
        $request = Request::instance();
        $result = [
            'msg' => $this->msg,
            'errorCode' => $this->errorCode,
            'request_url' => $request->url()

        ];

        return json($result, $this->code);
    }
}