<?php
declare (strict_types = 1);

namespace app\vocpand\validate;



class UserValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => 'require|number',
        'uname' => 'require',
        'uimg' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'code.isNotEmpty' => 'code 不能是空串'
    ];


}
