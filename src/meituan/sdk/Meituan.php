<?php
/**
 * Created by PhpStorm.
 * User: jimphei
 * Date: 2021-06-07
 * Time: 14:23
 */

namespace Jimphei\mallsdk\meituan\sdk;

use GuzzleHttp\Client;
use Jimphei\mallsdk\http\Request;

class Meituan
{
    protected $request;
    private $appkey;
    private $secret;
    private $secret2;
    const URL = 'https://runion.meituan.com/api';
    const OTO_URL = 'https://openapi.meituan.com/poi';

    public function __construct($appkey,$secret,$secret2)
    {
        //'gb8cwkj53x';
        $this->appkey = $appkey;
        $this->secret = $secret;
        $this->secret2 = $secret2;
        $this->request = new Request();
    }

    private function sign(array $params = []){
        unset($params["sign"]);
        ksort($params);
        $str = $this->secret; // $secret为分配的密钥
        foreach($params as $key => $value) {
            $str .= $key . $value;
        }
        $str .= $this->secret;
        $sign = md5($str);
        return $sign;
    }

    private function signTui(array $params = []){
        unset($params["sign"]);
        ksort($params);
        $str = $this->secret2; // $secret为分配的密钥
        foreach($params as $key => $value) {
            $str .= $key . $value;
        }
        $str .= $this->secret2;
        $sign = md5($str);
        return $sign;
    }

    /**
     * 查询订单
     * @param array $params
     * @return array|mixed
     */
    public function getOrders(array $params = []){
        $data = [
            'appkey' =>$this->appkey,
            'ts' => time(),
            'type' => $params['type']??'4',
            'startTime' => $params['startTime']??strtotime(date('Y-m-d')),//默认一天前
            'endTime' =>$params['endTime']??time(),
            'queryTimeType' => '1',
            'page' => $params['page']??'1',
            'limit' => $params['limit']??'50'
        ];
        $data['sign'] = $this->sign($data);
        $query = ['query'=>$data];
        $response = $this->request->get(self::URL.'/orderList',$query);
        return $response->array();
    }

    /**
     * 获取优惠券列表
     */
    public function couponList($sid,array $params = []){
        $data = [
            'appkey' =>$this->appkey,
            'ts' => time(),
            'type' => $params['type']??'4',
            'startTime' => $params['startTime']??strtotime(date('Y-m-d')),//默认一天前
            'endTime' =>$params['endTime']??time(),
            'queryTimeType' => '1',
            'page' => $params['page']??'1',
            'limit' => $params['limit']??'1',
            'sid' => $sid
        ];
        $data['sign'] = $this->sign($data);
        $response = $this->request->get(self::URL.'/couponList',$data);
        return $response->array();
    }

    /**
     * 接收订单结果
     */
    public function recevieOrder(array $data){
        $sign = $data['sign'];
        if($this->checkSign($sign,$data)){

        }
    }

    public function checkSign($sign,$data){
        unset($data['sign']);
        $result = $this->sign($data);
        return $result == $sign;
    }

    public function checkTuiSign($sign,$data){
        unset($data['sign']);
        $result = $this->signTui($data);
        return $result == $sign;
    }

    /**
     * 获取链接
     * @param $act_id
     * @param $sid
     * @param int $linkType
     */
    public function generateLink($act_id,$sid,$linkType=1){
        $data = [
            'actId' => $act_id,
            'key' => $this->appkey,
            'sid' => $sid,
            'linkType' => $linkType
        ];
        $data['sign'] = $this->sign($data);
        $url = 'https://runion.meituan.com/generateLink';
        $response = $this->request->get($url,$data);
        return $response->array();
    }

    /**
     * 生成小程序二维码
     * @param $act_id
     * @param $sid
     * @return array|mixed
     */
    public function miniCode($act_id,$sid){
        $data = [
            'actId' => $act_id,
            'key' => $this->appkey,
            'sid' => $sid
        ];
        $data['sign'] = $this->sign($data);
        $url = 'https://runion.meituan.com/miniCode';
        $response = $this->request->get($url,$data);
        return $response->array();
    }

    /**
     * 城市接口列表
     * @return array|mixed
     */
    public function cities(){
        $resp = $this->request->get(self::OTO_URL.'/city');
        return $resp->array();
    }

    /**
     * 行政区域列表
     * @param $city_id
     * @return array|mixed
     */
    public function areas($city_id){
        $data = [
            'cityid' =>$city_id
        ];
        $resp = $this->request->get(self::OTO_URL.'/district',$data);
        return $resp->array();
    }

    /**
     * 商圈列表
     * @param $city_id
     * @return array|mixed
     */
    public function quans($city_id){
        $data = [
            'cityid' =>$city_id
        ];
        $resp = $this->request->get(self::OTO_URL.'/area',$data);
        return $resp->array();
    }

    public function cates($city_id){
        $data = [
            'cityid' =>$city_id
        ];
        $resp = $this->request->get(self::OTO_URL.'/category',$data);
        return $resp->array();
    }

    /**
     * 获取团购列表
     * @param $city_id
     * @param $page
     * @param $pageSize
     * @param $key
     * @param int $cateid
     * @param int $districtid
     * @param string $devicetype
     */
    public function poiBycity($city_id,$page,$pageSize,$key,$cateid = 0,$districtid = 0,$devicetype = 'android'){

        $offset = ($page-1)*$pageSize;
        $data = [
            'appkey' => $this->appkey,
            'offset' =>$offset,
            'limit' => $pageSize,
            'query' => $key,
            'devicetype' => $devicetype,
            'city' => $city_id
        ];
    }


    /**
     * 订单列表查询(新)
     * https://runion.meituan.com/api/orderList
     * @param array $options
     */
    public function orderList(array $options)
    {
        $url = 'https://runion.meituan.com/api/orderList';
        //默认 10分钟前的订单
        $end_time_stamp = $options['end_time'] ?? time();
        $gap = $options['gap'] ?? 600;
        $start_time_stamp = $options['start_time'] ?? $end_time_stamp-$gap;
        $params = [
            'key' => $this->appkey,
            'ts' => time(),
            'type' => 4,//查询订单类型0 团购订单2 酒店订单4 外卖订单5 话费订单6 闪购订单
            'startTime' => $start_time_stamp,
            'endTime' => $end_time_stamp,
            'page' => 1,//分页参数，起始值从1开始
            'limit' => 100,//每页显示数据条数，最大值为100
            'queryTimeType' => 1,//1 按订单支付时间查询 2 按订单发生修改时间查询
        ];
        //var_dump($params);
        if(isset($options['type'])) $params['type'] = $options['type'];
        if(isset($options['page_size'])) $params['limit'] = $options['page_size'];
        if(isset($options['page_no'])) $params['page'] = $options['page_no'];
        if(isset($options['queryTimeType'])) $params['queryTimeType'] = $options['queryTimeType'];

        $params['sign'] = self::getSign($params);
//        trace($params);
        $client = new Client();
        $re = $client->request('get',$url,['query'=>$params]);
        $body = $re->getBody()->getContents();

        return json_decode($body,true);

    }

    private function getSign($params)
    {
        $secret = $this->secret;
        unset($params["sign"]);
        ksort($params);
        $str = $secret; // $secret为分配的密钥
        foreach($params as $key => $value) {
            $str .= $key . $value;
        }
        $str .= $secret;
        return md5($str);
    }


    public function findById(string $orderid,int $type){
        $url = 'https://runion.meituan.com/api/rtnotify';
        $params = [
            'key' => $this->appkey,
            'type' => $type,//查询订单类型0 团购订单2 酒店订单4 外卖订单5 话费订单6 闪购订单
            'full' => 1,
            'oid' => $orderid
        ];

        $params['sign'] = self::getSign($params);
//        trace($params);
        $client = new Client();
        $re = $client->request('get',$url,['query'=>$params]);
        $body = $re->getBody()->getContents();

        return json_decode($body,true);
    }

}