<?php
declare (strict_types = 1);

namespace app\vocpand\controller;

use app\vocpand\validate\UserValidate;
use think\facade\Db;
use think\Request;


class User extends CommonController
{
    public function info()
    {
        $temp = $this->user;
        $user = Db::name('user')->find($temp->uid);
        unset($user['openid']);
        return $this->resultJson(0, 'ok',$user);
    }

    /**
     * 完善用户幸喜
     */
    public function finish(Request $request)
    {
        $data = $request->post();
        (new UserValidate())->checkParam($data);
        $data['isfinish'] = 1;
        Db::name('user')->strict(false)->update($data);
        return $this->resultJson(0, 'ok');

    }
}
