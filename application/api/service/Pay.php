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
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');   //引入微信支付SDK

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

        return $this->makeWxPreOrder($status['orderPrice']);
    }

    /*
     * 构建微信支付订单信息
     * @param string $totalPrice 订单支付总金额
     */
    private function makeWxPreOrder($totalPrice)
    {
        //获取用户的openID
        $openId = TokenService::getCurrentTokenVal('openid');
        if (!$openId) {
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody('零食商贩');
        $wxOrderData->SetOpenid($openId);
        $wxOrderData->SetNotify_url('');
        return $this->getPaySignature($wxOrderData);
    }

    /*
     * 获取微信支付签名
     * @param array $wxOrderData 支付订单信息
     */
    private function getPaySignature($wxOrderData)
    {

        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        if ($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS') {
            Log::record($wxOrder, 'error');
            Log::record('获取预支付订单失败', 'error');
        }
        //记录prepay_id
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    /*
     * 生成签名
     * @param array $wxOrder
     */
    private function sign($wxOrder)
    {
        $jsApiData = new \WxPayJsApiPay();
        $jsApiData->SetAppid(config('wx.app_id'));
        $jsApiData->SetTimeStamp((string)time());

        $rand = md5(time() . mt_rand(0, 1000));
        $jsApiData->SetNonceStr($rand);
        $jsApiData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
        $jsApiData->SetSignType('md5');

        $sign = $jsApiData->MakeSign();
        $rawValues = $jsApiData->GetValues();
        $rawValues['paySign'] = $sign;

        unset($rawValues['appId']);
        return $rawValues;
    }

    /*
     * 将prepay_id写入到对应的订单信息表中，用于后面给用户推送模板信息
     */
    private function recordPreOrder($wxOrder)
    {
        OrderModel::where('id', '=', $this->orderID)->update(['prepay_id' => $wxOrder['prepay_id']]);
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