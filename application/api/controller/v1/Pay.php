<?php


namespace app\api\controller\v1;


use app\api\service\Pay as PayService;
use app\api\service\WxNotify;
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

        $pay = new PayService($id);
        return $pay->pay();
    }

    public function receiveNotify(){

        //1.检验库存，防止超卖
        //2.更改当前订单的status状态
        //3.执行成功将成功的信息返回给微信，反之将失败的信息返回
        //微信回调特点：post请求，xml格式

        $notify = new WxNotify();
        $notify->Handle();
    }
}
