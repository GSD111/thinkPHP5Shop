<?php


namespace app\api\service;


use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\api\service\Token as TokenService;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use think\Loader;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');   //引入微信支付SDK

class Pay
{
    private $orderID;
    private $orderNO;

    function __construct($orderID)
    {
        if (!$orderID) {
            throw new Exception('订单号不允许为NULL');
        }

        $this->orderID = $orderID;
    }

    public function pay()
    {
        //检测订单是否存在
        //订单信息与当前用户不匹配
        //订单状态为未支付
        //检测订单的库存量
        $this->checkOrderValid();
        $orderService = new OrderService();
        $status = $orderService->checkOrderStock($this->orderID);
        if (!$status['pass']) {

            return $status;
        }
    }

    /*
     * 提交订单调用微信支付
     */
    private function makeWxPreOrder()
    {
        //获取用户的openID
        $openId = TokenService::getCurrentTokenVal('openid');
        if (!$openId) {
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
    }

    /*
     * 订单支付，订单信息检测
     */
    private function checkOrderValid()
    {

        //检测订单信息是否存在
        $order = OrderModel::where('id', '=', $this->orderID)->find();
        if (!$order) {
            throw new OrderException();
        }

        //订单是否与用户匹配
        if (!TokenService::isValidOperate($order->user_id)) {
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' => 10003
            ]);
        }

        //订单支付状态检测
        if ($order->status != OrderStatusEnum::UNPAID) {
            throw new OrderException([
                'msg' => '订单已支付过了',
                'errorCode' => 80003,
                'error' => 400
            ]);
        }

        $this->orderNO = $order->order_no;
        return true;
    }
}