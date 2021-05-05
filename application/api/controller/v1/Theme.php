<?php


namespace app\api\controller\v1;


use app\api\model\Theme as ThemeModel;
use app\api\validate\IDCollection;
use app\lib\exception\ThemeException;

class Theme
{
    /*
     * 获取所有主题集合
     * @url ids? = id1,id2,id3...
     * @return 一组Theme模型
     */
    public function getSimpleList($ids = '')
    {
        (new IDCollection())->goCheck();

        $ids = explode(',', $ids);
        $result = ThemeModel::with('topicImg,headImg')->select($ids);
        if (!$result) {
            throw new ThemeException();
        }
        return $result;
    }

    /*
     * 获取某一个主题下的所有产品
     * @url theme/:id ....
     */
    public function getComplexOne($id)
    {
//        (new IDCollection())->goCheck();
        $result = ThemeModel::getThemeWithProduct($id);
        if (!$result) {
            throw new ThemeException();
        }
        return $result;
    }
}