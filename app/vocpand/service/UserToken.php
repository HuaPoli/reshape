<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 2021/5/9
 * Time: 16:32
 */

namespace app\vocpand\service;


use app\exception\HttpExceptions;
use think\facade\Config;
use app\vocpand\model\User;

class UserToken extends BaseToken
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;
    protected $role;
    function __construct($code,$role)
    {
        $wx = Config::get('wx');

        $this->code = $code;
        $this->role = $role;
        $this->wxAppID = $wx['app_id'];
        $this->wxAppSecret = $wx['app_secret'];
        $this->wxLoginUrl = sprintf(
            $wx['login_url'], $this->wxAppID, $this->wxAppSecret, $this->code);
    }

    public function get()
    {
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result, true);
        if (empty($wxResult)) {
            throw new HttpExceptions(400, '微信服务器内部错误', 199);
        }else{
            if(array_key_exists('errcode',$wxResult))
                throw new HttpExceptions(400, '传入的code失效', 3000);
            return $this->grantToken($wxResult);
        }

    }

    private function grantToken($wxResult)
    {
        // 此处生成令牌使用的是TP5自带的令牌
        // 如果想要更加安全可以考虑自己生成更复杂的令牌
        // 比如使用JWT并加入盐，如果不加入盐有一定的几率伪造令牌
        //        $token = Request::instance()->token('token', 'md5');
        $openid = $wxResult['openid'];
        $user = User::getByOpenID($openid);
        if (!$user)
            // 借助微信的openid作为用户标识
            // 但在系统中的相关查询还是使用自己的uid
        {
            $uid = $this->newUser($openid);
        }
        else {
            $uid = $user->id;
        }
        $cachedValue = $this->prepareCachedValue($wxResult, $uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }


    private function prepareCachedValue($wxResult, $uid)
    {
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        $cachedValue['role'] = $this->role;
        return $cachedValue;
    }

    private function newUser($openid)
    {
        $user =  User::create(['openid'=>$openid]);
        return $user->id;

    }

    // 写入缓存
    private function saveToCache($wxResult)
    {
        $key = self::generateToken();
        $value = json_encode($wxResult);
        $expire_in = config('setting.token_expire_in');
        $result = cache($key, $value, $expire_in);

        if (!$result){
            throw new HttpExceptions(400,'写入缓存失败',3000);
        }
        return $key;
    }
}