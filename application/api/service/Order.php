<?php


namespace app\api\service;


use app\api\model\Product;
use app\lib\exception\OrderException;

class Order
{
    protected $oProducts;   //客户端传递过来的商品信息参数
    protected $products;    //真实的商品信息(包括库存量)
    protected $uid;  //用户的id

    public function place($oProducts, $uid)
    {
        //$oproducts跟查询出来的$products进行对比，检验库存
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $this->uid = $uid;
    }

    /*
     * 获取订单的状态
     */
    private function getOrderStatus()
    {
        $status = [
            'pass' => true,
            'orderPrice' => 0,
            'pStatusArray' => []
        ];

        foreach ($this->oProducts as $oProduct) {
            $pStatus = $this->getProductStatus(
                $oProduct['product_id'], $oProduct['count'], $this->products
            );
            if (!$pStatus['haveStock']) {
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            array_push($status['pStatusArray'], $pStatus);
        }
        return $status;

    }

    /*
     * 获取商品的状态
     * @param string $oPID  订单商品的id
     * @param string $oCount 订单商品的数量
     * @param array  $products 订单商品的所有参数总和
     */

    private function getProductStatus($oPID, $oCount, $products)
    {
        $pIndex = -1;  //商品的编号
        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'totalPrice' => 0
        ];

        for ($i = 0; $i < count($products); $i++) {
            if ($oPID == $products[$i]['id']) {
                $pIndex = $i;
            }
        }

        //判断客户端传递的商品ID
        if ($pIndex == -1) {
            throw new OrderException([
                'msg' => 'id为' . $oPID . '的商品不存在,创建订单失败'
            ]);
        } else {
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['name'] = $product['name'];
            $pStatus['count'] = $oCount;
            $pStatus['totalPrice'] = $product['price'] * $oCount;
            if ($product['stock'] >= 0) {
                $pStatus['haveStock'] = true;
            }
        }

        return $pStatus;
    }

    /*
     * 根据订单信息查找真实的商品信息
     * @param array $oProducts
     */

    private function getProductsByOrder($oProducts)
    {
        $oPIDs = [];
        foreach ($oProducts as $item) {
            array_push($oPIDs, $item['product_id']);
        }

        $products = Product::all($oPIDs)
            ->visible(['id', 'price', 'stock', 'name', 'main_img_url'])
            ->toArray();

        return $products;
    }
}