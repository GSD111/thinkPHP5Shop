<?php


namespace app\api\model;

class Order extends BaseModel
{
    protected $hidden = ['user_id', 'delete_time', 'update_time'];

    protected $autoWriteTimestamp = true;

    /*
     * 转换订单商品信息数据格式为JSON
     */
    public function getSnapItemsAttr($value)
    {
        if (empty($value)) {
            return null;
        }

        return json_decode($value);
    }

    /*
     * 转换订单收货地址数据格式为JSON
     */
    public function getSnapAddressAttr($value)
    {
        if (empty($value)) {
            return null;
        }

        return json_decode($value);
    }

    /*
     * 用户订单分页
     * @param string $uid   用户的id
     * @param string $page  页数
     * @param string $size  显示的条数
     */

    public static function getSummaryByUser($uid, $page, $size)
    {

        $pagingData = self::where('user_id', '=', $uid)
            ->order('create_time desc')
            ->paginate($size, true, ['page' => $page]);

        return $pagingData;
    }
}