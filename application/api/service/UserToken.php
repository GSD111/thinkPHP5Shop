<?php


namespace app\api\service;


use app\lib\exception\WeChatException;
use think\Exception;

class UserToken
{
    protected $code;
    protected $wxAppId;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    /**
     * @param string $url get请求地址
     * @param int $httpCode 返回状态码
     * @return mixed
     */
    function curl_get($url, &$httpCode = 0)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //不做证书校验,部署在linux环境下请改为true
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $file_contents = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $file_contents;
    }


    function __construct($code)
    {
        $this->code = $code;
        $this->wxAppId = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'), $this->wxAppId, $this->wxAppSecret, $this->code);
    }

    public function getUserToken()
    {
        $result = $this->curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result, true);
        if (empty($wxResult)) {
            throw new Exception('获取session_key和openID异常，微信内部服务错误');
        } else {
            $loginFail = array_key_exists('errcode', $wxResult);
            if ($loginFail) {
                $this->processLoginError($wxResult);
            } else {
                $this->grantToken($wxResult);
            }
        }
    }

    private function grantToken($wxResult)
    {
        //1.拿到openID
        //2.检查数据库openID 是否存在
        //3.若果存在则不处理，不存在则添加一条user记录
        //4.生成令牌，准备缓存数据，写入缓存
        //5.将token令牌返回到客户端
    }

    private function processLoginError($wxResult)
    {
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }
}