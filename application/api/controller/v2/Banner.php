<?php


namespace app\api\controller\v2;


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

        return 'this is v2 Version';


    }
}