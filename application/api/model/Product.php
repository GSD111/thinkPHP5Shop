<?php


namespace app\api\model;


class Product extends BaseModel
{
    protected $hidden = ['delete_time', 'category_id', 'from', 'update_time', 'create_time', 'pivot'];

    public function getMainImgUrlAttr($value, $data)
    {
        return $this->prefixImgUrl($value, $data);
    }

    public static function getMostRecent($count){

        $products = self::limit($count)->order('create_time desc')->select();

        return $products;
    }
}