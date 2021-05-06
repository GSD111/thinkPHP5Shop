<?php


namespace app\api\model;


class User extends BaseModel
{

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