<?php


namespace app\api\model;


class Product extends BaseModel
{
    protected $hidden = ['delete_time', 'category_id', 'from', 'update_time', 'create_time', 'pivot'];

    /*
     * @param string $value 所要读取的图片值
     * @param array $data 数据集合
     * 产品图片读取器
     */
    public function getMainImgUrlAttr($value, $data)
    {
        return $this->prefixImgUrl($value, $data);
    }

    /*
     * @param string $count 产品的数量
     * 获取最新产品的数量
     */
    public static function getMostRecent($count)
    {

        $products = self::limit($count)->order('create_time desc')->select();

        return $products;
    }

    /*
     * @param string $categoryId  分类的ID
     * 根据分类ID获取对应分类下的商品信息
     */
    public static function getProductsByCategoryID($categoryId)
    {

        $products = self::where('category_id', '=', $categoryId)->select();

        return $products;
    }
}