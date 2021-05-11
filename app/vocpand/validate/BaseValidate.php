<?php
declare (strict_types = 1);

namespace app\vocpand\validate;

use think\Validate;
use think\exception\ValidateException;
use app\exception\HttpExceptions;
class BaseValidate extends Validate
{

    protected function isNotEmpty($value, $rule, $data=[])
    {
        if(empty($value))
            return false;
        return true;
    }

    public function checkParam($data)
    {
        try {
            $this->check($data,$this->rule);
            if(!$this->check($data,$this->rule)){
                $msg = $this->getError();
                throw new HttpExceptions(400,$msg , 2);
            }


        } catch (ValidateException $e) {
            throw new HttpExceptions(400, $e->getMessage(), 2);
        }

    }

}
