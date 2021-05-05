<?php


namespace app\api\controller\v1;


use app\api\validate\IDCollection;
use app\api\model\Theme as ThemeModel;
use app\lib\exception\ThemeException;

class Theme
{
    /*
     * @url ids? = id1,id2,id3...
     * @return 一组Theme模型
     */
    public function getSimpleList($ids=''){
        (new IDCollection())->goCheck();

        $ids = explode(',',$ids);
        $result = ThemeModel::with('topicImg,headImg')->select($ids);
        if(!$result){
            throw new ThemeException();
        }
        return $result;
    }
}