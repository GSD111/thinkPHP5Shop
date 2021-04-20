<?php


namespace app\api\controller\validate;


use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        //获取http传入的参数
        $request = Request::instance();
        $params = $request->param();

        //验证接收到的参数
        $result = $this->batch()->check($params);
//        var_dump($result);die;
        if (!$result) {
            $e = new ParameterException([
                'msg' => $this->error
            ]);
            throw $e;
        } else {
            return true;
        }
    }
}