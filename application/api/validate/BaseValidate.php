<?php


namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{

    /*
     * 获取用户请求的所有参数
     */
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


    /*
     * 验证传递参数是否为正整数
     */
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

    /*
     * 验证传递参数是否为空
     */
    protected function isNotEmpty($value, $rule = '', $data = '', $field = '')
    {
//        var_dump($value,$field);die;
        if (empty($value)) {
            return false;
        } else {
            return true;
        }
    }


    /*
     * 获取验证规则中的参数
     * @param array $arrays
     */
    public function getDataByRule($arrays)
    {
        if (array_key_exists('user_id', $arrays) || array_key_exists('uid', $arrays)) {
            //不允许包含user_id和uid,防止恶意覆盖外键user_id
            throw new ParameterException([
                'msg' => '参数中包含非法参数名user_id或者uid'
            ]);
        }

        $newArray = [];
        foreach ($this->rule as $key => $value) {
            $newArray[$key] = $arrays[$key];
        }

        return $newArray;
    }

    /*
     * 手机号验证
     * @param string $value
     */
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|6|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }

    }
}