<?php


namespace app\api\controller\validate;


use think\Validate;

class TestValidate extends Validate
{

    //验证器
    protected $rule = [
        'name'=> 'require|max:10',
        'email'=>'email'
    ];
}