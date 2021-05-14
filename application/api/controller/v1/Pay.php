<?php


namespace app\api\controller\v1;


use app\api\validate\IDMustBePositiveInt;

class Pay extends BaseController
{
    /*
      * 前置方法
      */
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOrder']
    ];

    /*
     * 向微信端提交预订单信息
     * @param string $id
     */
    public function getPreOrder($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
    }
}