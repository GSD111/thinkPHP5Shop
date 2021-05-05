<?php


namespace app\api\validate;


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
//        var_dump($params);die;
        if (!$result) {
            $e = new ParameterException([
                'msg' => $this->error
            ]);
            throw $e;
        } else {
            return true;
        }
    }

    protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
    {
//        var_dump($value,$field);die;
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            return false;
        }
//        return $field . '必须是正整数';
    }
}