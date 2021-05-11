<?php
declare (strict_types = 1);

namespace app\reshape\controller;

use think\facade\Db;
use think\Request;

class Recruitment extends BaseContorller
{
    public function zx(Request $request)
    {
        $sid = $request->post('sid');
        if(!isset($sid))
            return $this->resultJson(199, '学号必须填写', []);
        $stu = Db::name('zx')->where('sid', $request->post('sid'))->find();
        if(!empty($stu))
            return $this->resultJson(199, '我们已收到你提交申请，请注意留意信息', []);
        $data = $request->post();
        $data['sid'] = intval($data['sid']);
        $count = Db::name('zx')->strict(false)->insert($data);
        if($count > 0 )
            return $this->resultJson(0, '提交成功', []);
        return $this->resultJson(199, '提交失败', []);

    }

}
