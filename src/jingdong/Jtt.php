<?php
/**
 * Created by PhpStorm.
 * User: jimphei
 * Date: 2021-06-08
 * Time: 16:29
 */

namespace Jimphei\mallsdk\jingdong;


use Jimphei\mallsdk\http\Request;

class Jtt
{
    protected $request;
    protected $appId;
    protected $appKey;
    protected $unionId;
    protected $authKey;
    const URL = 'http://japi.jingtuitui.com/api/';
    public function __construct($appId, $appKey, $unionId='', $authKey='')
    {
        $this->appId = $appId;
        $this->appKey = $appKey;
        $this->request = new Request();
        if ($unionId){
            $this->unionId = $unionId;
        }
        if ($authKey){
            $this->authKey = $authKey;
        }
    }

    // 首焦banner
    public function getBanner(int $type,int $num){
        $data = [
            'appid' => $this->appId,
            'appkey' => $this->appKey,
            'v' => 'v2',
            'type' => $type,
            'num' => $num,
        ];
        $url = self::URL.'get_banner';
        $result = $this->request->get($url, $data);
        return $result->array();
    }

    // 超级分类
    public function getCategory(int $goods_type=0){
        $data = [
            'appid' => $this->appId,
            'appkey' => $this->appKey,
            'v' => 'v2',
            'goods_type' => $goods_type,
        ];
        $url = self::URL.'get_super_category';
        $result = $this->request->get($url, $data);
        return $result->array();
    }

    // 联盟搜索
    public function search(int $cid1,int $cid2,int $cid3,int $pageIndex,int $pageSize,string $skuIds,string $keyword,string $pricefrom,string $priceto,string $owner,string $sortName,string $sort,int $isCoupon,int $isPG,string $forbidTypes,int $deliveryType){

        $data = [
            'appid' => $this->appId,
            'appkey' => $this->appKey,
            'v' => 'v3'
        ];
        if ($cid1){
            $data['cid1'] = $cid1;
        }
        if ($cid2){
            $data['cid2'] = $cid2;
        }
        if ($cid3){
            $data['cid3'] = $cid3;
        }
        if ($pageIndex){
            $data['pageIndex'] = $pageIndex;
        }
        if ($pageSize){
            $data['pageSize'] = $pageSize;
        }
        if ($skuIds){
            $data['skuIds'] = $skuIds;
        }
        if ($keyword){
            $data['keyword'] = $keyword;
        }
        if ($pricefrom){
            $data['pricefrom'] = $pricefrom;
        }
        if ($priceto){
            $data['priceto'] = $priceto;
        }
        if ($owner){
            $data['owner'] = $owner;
        }
        if ($sortName){
            $data['sortName'] = $sortName;
        }
        if ($sort){
            $data['sort'] = $sort;
        }
        if ($isCoupon){
            $data['isCoupon'] = $isCoupon;
        }
        if ($isPG){
            $data['isPG'] = $isPG;
        }
        if ($forbidTypes){
            $data['forbidTypes'] = $forbidTypes;
        }
        if ($deliveryType){
            $data['deliveryType'] = $deliveryType;
        }
        $url = self::URL.'jd_goods_query';
        $result = $this->request->get($url, $data);
        return $result->array();
    }

    // 热搜记录
    public function getHotSearch(){
        $data = [
            'appid' => $this->appId,
            'appkey' => $this->appKey,
        ];
        $url = self::URL.'hot_search';
        $result = $this->request->get($url, $data);
        return $result->array();
    }

    // 奖励商品
    public function subsidyGoods(int $pageIndex,int $pageSize,string $JID='',string $keyword='',int $goods_type=0){
        $data = [
            'appid' => $this->appId,
            'appkey' => $this->appKey,
            'v' => 'v2',
            'goods_type' => $goods_type,
        ];
        if ($pageIndex){
            $data['pageIndex'] = $pageIndex;
        }
        if ($pageSize){
            $data['pageSize'] = $pageSize;
        }
        if ($JID){
            $data['JID'] = $JID;
        }
        if ($keyword){
            $data['keyword'] = $keyword;
        }
//        if ($goods_type){
//            $data['goods_type'] = $goods_type;
//        }
        $url = self::URL.'subsidy_goods';
        $result = $this->request->get($url, $data);
        return $result->array();
    }

    // 各大榜单
    public function todayTop(int $pageIndex,int $pageSize,string $eliteId='',int $goods_type=0, int $goods_second_type=0){
        $data = [
            'appid' => $this->appId,
            'appkey' => $this->appKey,
            'v' => 'v2',
        ];
        if ($pageIndex){
            $data['pageIndex'] = $pageIndex;
        }
        if ($pageSize){
            $data['pageSize'] = $pageSize;
        }
        if ($eliteId){
            $data['eliteId'] = $eliteId;
        }
        if ($goods_type){
            $data['goods_type'] = $goods_type;
        }
        if ($goods_second_type){
            $data['goods_second_type'] = $goods_second_type;
        }
        $url = self::URL.'today_top';
        $result = $this->request->get($url, $data);
        return $result->array();
    }

    // 特色栏目
    public function goodsListType(string $type,int $pageIndex,int $pageSize,string $keyword='',int $goods_type=0, int $goods_second_type=0,string $sortName='',string $sort='',string $price_start='',string $price_end='',int $brand_id=0,int $shop_id=0){
        $data = [
            'appid' => $this->appId,
            'appkey' => $this->appKey,
            'v' => 'v2',
            'eliteId' => $type
        ];
        if ($pageIndex){
            $data['pageIndex'] = $pageIndex;
        }
        if ($pageSize){
            $data['pageSize'] = $pageSize;
        }
        if ($keyword){
            $data['keyword'] = $keyword;
        }
        if ($goods_type){
            $data['goods_type'] = $goods_type;
        }
        if ($goods_second_type){
            $data['goods_second_type'] = $goods_second_type;
        }
        if ($sortName){
            $data['sortName'] = $sortName;
        }
        if ($sort){
            $data['sort'] = $sort;
        }
        if ($price_start){
            $data['price_start'] = $price_start;
        }
        if ($price_end){
            $data['price_end'] = $price_end;
        }
        if ($brand_id){
            $data['brand_id'] = $brand_id;
        }
        if ($shop_id){
            $data['shop_id'] = $shop_id;
        }
        $url = self::URL.'get_goods_list';
        $result = $this->request->get($url, $data);
        return $result->array();
    }

    // 商品基础信息
    public function getGoodsInfo(string $skuIds){
        $data = [
            'appid' => $this->appId,
            'appkey' => $this->appKey,
            'v' => 'v3',
            'skuIds' => $skuIds,
        ];
        $url = self::URL.'get_goods_info';
        $result = $this->request->get($url, $data);
        return $result->array();
    }

    // 商品详情页
    public function getWareStyle(string $skuIds){
        $data = [
            'appid' => $this->appId,
            'appkey' => $this->appKey,
            'v' => 'v2',
            'skuIds' => $skuIds,
        ];
        $url = self::URL.'get_ware_style';
        $result = $this->request->get($url, $data);
        return $result->array();
    }

    // 优惠券信息
    public function getCouponInfo(string $couponUrls){
        $data = [
            'appid' => $this->appId,
            'appkey' => $this->appKey,
            'v' => 'v3',
            'couponUrls' => $couponUrls,
        ];
        $url = self::URL.'get_coupom_info';
        $result = $this->request->get($url, $data);
        return $result->array();
    }

    // 高效转链
    public function getGoodsLink(string $uid,string $gid,string $coupon_url=''){

        $gid = 'item.jd.com/' . $gid . '.html?rid=11208';


        $data = [
            'appid' => $this->appId,
            'appkey' => $this->appKey,
            'unionid' => $this->unionId,
            'positionid' => $uid,
            'gid' => $gid,
        ];
        if ($coupon_url){
            $data['coupon_url'] = $coupon_url;
        }
        $url = self::URL.'get_goods_link';
        $result = $this->request->get($url, $data);
        return $result->array();
    }

    // 创建京东pid--京推推用不上
    private function creatPid($uid){
        $url = "http://api.josapi.net/createpid?unionId={$this->unionId}&key={$this->authKey}&type=4&spaceNameList=pid_{$uid}";
        $result = $this->request->get($url);
        $jd_pid = "";
        if($result['error']=="0")
        {
            $jd_idx = "pid_{$uid}";
            $jd_pid = $result['data']['resultList'][$jd_idx];
            $data=array(
                'jd_pid'=>$jd_pid
            );
            $ret = Db::name('TkyUser')->where('user_id',$uid)->update($data);
            if(!$ret){
                return "";
            }
        }
        return $jd_pid;
    }

    public function pullOrder($unionid,$authkey,$startTime,$endTime,int $page = 1,int $pageSize=50){
        $url = self::URL.'get_order_row';
        $base = [
            'appid' => $this->appId,
            'appkey' => $this->appKey,
            'v' => 'v2'
        ];
        $data = [
            'startTime' =>$startTime,
            'endTime' =>$endTime,
            'pageIndex' =>$page,
            'pageSize' =>$pageSize,
            'type' =>1,
            'fields' =>'goodsInfo',
            'unionid' =>$unionid,
            'key' => $authkey
        ];
        $data = array_merge($base,$data);
        $ret = $this->request->get($url,$data);
        var_dump($ret);
    }
}