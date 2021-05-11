<?php
declare (strict_types = 1);

namespace app\reshape\validate;

use think\Validate;

class ProductValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'title|标题' => 'require|max:50' ,
        'subtitle|描述' => 'max:100' ,
        'author|作者姓名' => 'max:16' ,
        'category_id|分类' => 'require|number'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];
}
