<?php


namespace app\api\model;


class User extends BaseModel
{


    /*
     * 关联用户地址模型
     */
    public function address(){

        return $this->hasOne('UserAddress','user_id','id');
    }
    /*
     * 根据openID查询用户信息
     * @param string $openid
     */
    public static function getByOpenID($openid)
    {

        $user = self::where('openid', '=', $openid)->find();
        return $user;
    }
}