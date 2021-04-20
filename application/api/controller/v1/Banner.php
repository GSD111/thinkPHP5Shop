<?php


namespace app\api\controller\v1;


use app\api\controller\validate\IDMustBePositiveInt;
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
        if(is_numeric($id) && is_int($id + 0) >0){
            $banner = BannerModel::getBannerById($id);
            if(!$banner){
                throw new BannerMissException();
            }
            return $banner;
        }else{
            throw new Exception('参数必须是整数');
        }


//        return $id;
        //独立验证

////        $validate = new Validate([
////            'name'=> 'require|max:10',
////            'email'=>'email'
////        ]);
//        $validate = new IDMustBePositiveInt();
//        $result = $validate->batch()->check($data);
//        var_dump($result);
//        echo $validate->getError();
////        var_dump($validate->getError());
//        if($result){
//            echo 1;
//        }else{
//            echo 0;
//        }
    }
}