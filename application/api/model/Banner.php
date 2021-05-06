<?php


namespace app\api\model;


class Banner extends BaseModel
{
    protected $hidden=['id','update_time','delete_time'];

    /*
     * 定义关联模型
     */
    public function items()
    {
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

    /*
     * 获取传入的ID值获取对应的banner信息
     * @param string $id
     */
    public static function getBannerById($id)
    {
        //根据ID 查询对应的banner信息
//        $result = Db::table('banner_item')->where('banner_id', '=', $id)->select();
        //闭包
//        $result = Db::table('banner_item')->where(function ($query) use ($id) {
//            $query->where('banner_id', '=', $id);
//        })->select();
        $result = self::with(['items','items.img'])->find($id);
//        $result->hidden(['update_time','delete_time']);
        return $result;
    }

}