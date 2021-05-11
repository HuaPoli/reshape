<?php
declare (strict_types = 1);

namespace app\reshape\validate;

use think\Validate;

class StepValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'step|步骤' => 'require|number',
        'step_desc|步骤介绍' => 'require',
        'product_id|作品id' => 'require|number',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];
}
