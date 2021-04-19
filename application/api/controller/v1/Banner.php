<?php


namespace app\api\controller\v1;


use app\api\controller\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;

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

        (new IDMustBePositiveInt())->goCheck();
        $banner = BannerModel::getBannerById($id);
        if(!$banner){
            throw new BannerMissException();
        }
        return $banner;
//        return $id;
        //独立验证
//        $data = [
//            'id'=> $id,
//        ];
////        $validate = new Validate([
////            'name'=> 'require|max:10',
////            'email'=>'email'
////        ]);
//        $validate = new IDMustBePositiveInt();
//        $result = $validate->batch()->check($data);
////        echo $validate->getError();
////        var_dump($validate->getError());
//        if($result){
//            echo 1;
//        }else{
//            echo 0;
//        }
    }
}