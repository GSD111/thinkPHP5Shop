<?php


namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{

    /*
     * 生成Token令牌
     */
    public static function generateToken()
    {
        //32个字符组成一组随机字符串
        $randChars = getRandChar(32);

        //用三组字符串进行MD5加密
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        $salt = config('secure.token_salt');

        return md5($randChars . $timestamp . $salt);
    }


    /*
     * @param string  $key
     * 获取当前Token中的值
     */
    public static function getCurrentTokenVal($key)
    {

        $token = Request::instance()->header('token');
        $values = Cache::get($token);
        if (!$values) {
            throw new TokenException();
        } else {
            if (!is_array($values)) {
                $values = json_decode($values, true);
            }
            if (array_key_exists($key, $values)) {
                return $values[$key];
            } else {
                throw new Exception('尝试获取的token变量并不存在');
            }

        }
    }

    /*
     * 获取当前请求用户的Uid
     */
    public static function getCurrentUid()
    {
        $uid = self::getCurrentTokenVal('uid');
        return $uid;
    }


    /*
     * 需要验证用户和CMS管理员都可以访问的接口权限
    */
    public static function needPrimaryScope()
    {
        $scope = self::getCurrentTokenVal('scope');
        if ($scope) {
            if ($scope >= ScopeEnum::User) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }

    /*
    * 只有用户才可以进行访问的接口权限
    */
    public static function needExclusiveScope()
    {
        $scope = self::getCurrentTokenVal('scope');
        if ($scope) {
            if ($scope == ScopeEnum::User) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }

}