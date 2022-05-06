<?php
/**
 * 项目 TKSAAS
 * Author jimphei
 * Date 2020-06-04
 */

namespace Jimphei\mallsdk;
use Jimphei\mallsdk\jingdong\JingDong;
use Com\Pdd\Pop\Sdk\PopHttpClient;
use Jimphei\mallsdk\meituan\sdk\Meituan;


class EpFactory
{
    private static $instance;
    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        if(!in_array($name,['taobao','jingdong','suning','pinduoduo','vip','meituan'])){
            throw new \InvalidArgumentException("不存在的静态方法");
        }
        $obj = self::getInstance();

        return $obj->build($name,...$arguments);
    }

    public static function taobao($config){
        $name = 'taobao';
        $obj = self::getInstance();

        return $obj->build($name,...$config);
    }

    public static function pinduoduo($config){
        $name = 'pinduoduo';
        $obj = self::getInstance();

        return $obj->build($name,...$config);
    }

    public static function jingdong($config){
        $name = 'jingdong';
        $obj = self::getInstance();

        return $obj->build($name,...$config);
    }
    public static function vip($config){
        $name = 'vip';
        $obj = self::getInstance();

        return $obj->build($name,...$config);
    }

    public static function suning($config){
        $name = 'suning';
        $obj = self::getInstance();
        return $obj->build($name,...$config);
    }

    public static function meituan($config){
        $name = 'meituan';
        $obj = self::getInstance();
        return $obj->build($name,...$config);
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function build($name,$config){

        if(empty($config)){
            throw new \InvalidArgumentException("缺少必要的配置参数");
        }
        if ($name == "taobao") {
            if (!array_key_exists('app_key', $config) || !array_key_exists('app_secret', $config)) {
                throw new \InvalidArgumentException('The top client requires api keys.');
            }
            require_once(dirname(__FILE__).'/taobao/TopSdk.php'); //引用淘宝开放平台 API SDK
            $c = new \TopClient;
            $c->appkey = $config['app_key'];
            $c->secretKey = $config['app_secret'];
            $c->format = isset($config['format']) ? $config['format'] : 'json';
            return $c;
        }
        if ($name == "pinduoduo") {
            if (!array_key_exists('client_id', $config) || !array_key_exists('client_secret', $config)) {
                throw new \InvalidArgumentException('The pinduoduo client requires client_id and client_secret.');
            }
            return new PopHttpClient($config['client_id'],$config['client_secret']);
        }
        if ($name == "jingdong") {
            if (!array_key_exists('app_key', $config) || !array_key_exists('app_secret', $config)) {
                throw new \InvalidArgumentException('The jingdong client requires app_key and app_secret.');
            }
            //$client = new JingDong($config['app_key'],$config['app_secret']);
            //return $client;
            $client = JingDong::getInstance();
            $client->setConfig($config['app_key'],$config['app_secret']);
            return $client;
        }
        if ($name == "vip") {
            if (!array_key_exists('app_key', $config) || !array_key_exists('app_secret', $config)) {
                throw new \InvalidArgumentException('The vip client requires app_key and app_secret.');
            }
        }
        if ($name == "suning") {
            if (!array_key_exists('app_key', $config) || !array_key_exists('app_secret', $config)) {
                throw new \InvalidArgumentException('The suning client requires app_key and app_secret.');
            }
        }
        if($name == 'meituan'){
            if (!array_key_exists('appkey', $config) || !array_key_exists('secret', $config) || !array_key_exists('secret2', $config)) {
                throw new \InvalidArgumentException('The suning client requires app_key and app_secret.');
            }
            return new Meituan($config['appkey'],$config['secret'],$config['secret2']);
        }
    }

    public static function test(){
        echo 'this is test';
    }


}