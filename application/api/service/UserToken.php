<?php


namespace app\api\service;


use app\api\model\User as UserModel;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;

class UserToken extends Token
{
    protected $code;
    protected $wxAppId;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    /*
     * 初始化加载WeChat配置信息
     */
    function __construct($code)
    {
        $this->code = $code;
        $this->wxAppId = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'), $this->wxAppId, $this->wxAppSecret, $this->code);
    }

    /*
     * @param array $wxResult 根据用户请求微信端返回的数据集
     * 获取微信用户的身份信息
     */
    public function getUserToken()
    {
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result, true);
        if (empty($wxResult)) {
            throw new Exception('获取session_key和openID异常，微信内部服务错误');
        } else {
            $loginFail = array_key_exists('errcode', $wxResult);
            if ($loginFail) {
                $this->processLoginError($wxResult);
            } else {
                return $this->grantToken($wxResult);
            }
        }
    }

    /*
     * @param array $wxResult 根据用户请求微信端返回的数据集
     * 获取到微信用户信息后颁发Token令牌
     */
    private function grantToken($wxResult)
    {
        //1.拿到openID
        //2.检查数据库openID 是否存在
        //3.若果存在则不处理，不存在则添加一条user记录
        //4.生成令牌，准备缓存数据，写入缓存
        //5.将token令牌返回到客户端
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if ($user) {
            $uid = $user->id;
        } else {
            $uid = $this->newUser($openid);
        }
        $cacheValue = $this->prepareCacheValue($wxResult, $uid);
        $token = $this->saveToCache($cacheValue);

        return $token;
    }

    /*
     * 准备缓存数据
     * @param array $wxResult
     * @param string $uid
     */
    private function prepareCacheValue($wxResult, $uid)
    {
        $CacheValue = $wxResult;
        $CacheValue['uid'] = $uid;
        $CacheValue['scope'] = 16;

        return $CacheValue;
    }

    /*
     *写入缓存
     */
    private function saveToCache($CacheValue)
    {

        $key = self::generateToken();
        $value = json_encode($CacheValue);
        $expire_in = config('setting.token_expire_in');

        $result = cache($key, $value, $expire_in);
        if (!$result) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }

        return $key;

    }


    /*
     * @param string $openid
     * 插入一条新的用户信息并且将新插入的用户ID返回
     */
    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid' => $openid,
        ]);

        return $user->id;
    }

    /*
     * @param array $wxResult 根据用户请求微信端返回的数据集
     * 获取用户信息错误将错误信息返回
     */
    private function processLoginError($wxResult)
    {
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }
}