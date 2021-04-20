<?php


namespace app\api\controller\validate;

class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger '
    ];

    protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
    {
//        var_dump($value,$field);die;
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
//            echo '111';die;
        }
            return $field . '必须是正整数';
    }


}