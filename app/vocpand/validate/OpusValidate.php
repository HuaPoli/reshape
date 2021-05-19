<?php
declare (strict_types = 1);

namespace app\vocpand\validate;



class OpusValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'title' => 'require|max:100',
        'content' => 'require',
        'file_type' => 'require',
        'city' => 'require',
        'country' => 'require',
        'user_id' => 'require|number',
        'category_id' => 'require|number'
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
