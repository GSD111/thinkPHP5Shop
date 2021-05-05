<?php


namespace app\api\controller\v1;


use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;
use think\Exception;

class Banner
{
    /*
     * 获取指定id的Banner信息
     * @url /banner/:id
     * @http GET
     * @id banner的id值 number
     */
    public function getBanner($id)
    {
//        (new IDMustBePositiveInt())->goCheck();
//        $banner = BannerModel::getBannerById($id);
//        if (!$banner) {
//            throw new BannerMissException();
//        }
//        return $banner;
        if (is_numeric($id) && is_int($id + 0) > 0) {
            $banner = BannerModel::getBannerById($id);
            if (!$banner) {
                throw new BannerMissException();
            }
            return $banner;
        } else {
            throw new Exception('参数必须是整数');
        }


    }
}