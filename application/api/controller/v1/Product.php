<?php


namespace app\api\controller\v1;


use app\api\model\Product as ProductModel;
use app\api\validate\Count;
use app\lib\exception\ProductException;

class Product
{

    /*
     * @param string $count 获取的数量 默认获取15条
     * 获取最新产品的信息
     */
    public function getRecent($count = 15)
    {
        (new Count())->goCheck();
        $products = ProductModel::getMostRecent($count);
        if ($products->isEmpty()) {
            throw new ProductException();
        }
//        $collection= collection($products);
        $products = $products->hidden(['summary']);
        return $products;
    }

    /*
     * @param string $id 分类的ID
     * 获取分类下面的商品信息
     */
    public function getAllInCategory($id)
    {
//        (new IDMustBePositiveInt())->goCheck();

        $products = ProductModel::getProductsByCategoryID($id);
        if ($products->isEmpty()) {
            throw new ProductException();
        }

        $products = $products->hidden(['summary']);

        return $products;
    }

    /*
     * 获取某一产品的详细信息
     */
    public function getOne($id)
    {
//        (new IDMustBePositiveInt())->goCheck();

        $productDetail = ProductModel::getProductDetail($id);
        if (!$productDetail) {
            throw new ProductException();
        }

        return $productDetail;
    }
}