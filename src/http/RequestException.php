<?php
// +----------------------------------------------------------------------
// | Bwsaas
// +----------------------------------------------------------------------
// | Copyright (c) 2015~2020 http://www.buwangyun.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Gitee ( https://gitee.com/buwangyun/bwsaas )
// +----------------------------------------------------------------------
// | Author: buwangyun <hnlg666@163.com>
// +----------------------------------------------------------------------
// | Date: 2020-9-28 10:55:00
// +----------------------------------------------------------------------
namespace Jimphei\mallsdk\http;

class RequestException
{
    public $exception;

    public function __construct($exception)
    {
        $this->exception = $exception;
    }

    public function getCode()
    {
        return $this->exception->getCode();
    }

    public function getMessage()
    {
        return $this->exception->getMessage();
    }

    public function getFile()
    {
        return $this->exception->getFile();
    }

    public function getLine()
    {
        return $this->exception->getLine();
    }

    public function getTrace()
    {
        return $this->exception->getTrace();
    }

    public function getTraceAsString()
    {
        return $this->exception->getTraceAsString();
    }
}
