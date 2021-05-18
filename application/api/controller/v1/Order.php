<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\api\service\Token as TokenService;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\OrderPlace;
use app\api\validate\PagingParameter;
use app\lib\exception\OrderException;

class Order extends BaseController
{
    // 1.用户在选择商品后，向API提交所选的商品信息
    // 2.API在接收到的数据后，检查商品的库存量
    // 3.有库存就把商品信息添加到订单表中，返回客户端信息，提示可以进行支付
    // 4.调用支付接口，进行支付
    // 5.还需再次进行库存量的检查
    // 6.如果有库存服务端进行微信支付接口调用，进行支付，如库存不足直接返回客户端库存不足信息
    // 7.小程序根据服务器返回的结果，拉起微信支付
    // 8.微信支付后微信会返回给我们一个结果(异步)
    // 9.支付成功：再次对库存量进行检查,如库存不足则通知客户端库存不足，并且发起退款。
    // 10.成功：扣除相应的库存量

    /*
     * 前置方法
     */
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder'],
        'checkPrimaryScope' => ['only' => 'getSummaryByUser']
    ];

    /*
     * 获取用订单的分页数据
     * @param string $page 默认值为1
     * @param string  $size 默认显示15条数据
     */
    public function getSummaryByUser($page = 1, $size = 15)
    {
        (new PagingParameter())->goCheck();
        $uid = TokenService::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByUser($uid, $page, $size);
        if ($pagingOrders->isEmpty()) {
            return [
                'data' => [],
                'current_page' => $pagingOrders->getCurrentPage()
            ];
        }
        $data = $pagingOrders->hidden(['snap_items', 'snap_address', 'prepay_id'])->toArray();
        return [
            'data' => $data,
            'current_page' => $pagingOrders->getCurrentPage()
        ];
    }

    /*
     * 获取订单详情
     * @param string $id  订单ID
     */

    public function getOrderDetail($id)
    {

        (new IDMustBePositiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if (!$orderDetail) {
            throw new OrderException();
        }

        return $orderDetail->hidden(['prepay_id']);
    }

    /*
     * 下订单操作
     */
    public function placeOrder()
    {
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();

        $order = new OrderService();
        $status = $order->place($uid, $products);

        return $status;
    }
}