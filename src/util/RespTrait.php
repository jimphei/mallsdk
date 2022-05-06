<?php
/**
 * Created by PhpStorm.
 * User: jimphei
 * Date: 2021-06-08
 * Time: 12:08
 */

namespace Jimphei\mallsdk\util;


trait RespTrait
{
    public function error(int $code = 1,$msg = ''){
        return ['code'=>$code,'msg'=>$msg];
    }
}