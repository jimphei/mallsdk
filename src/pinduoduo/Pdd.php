<?php
/**
 * Created by PhpStorm.
 * User: jimphei
 * Date: 2021-06-08
 * Time: 12:49
 */

namespace Jimphei\mallsdk\pinduoduo;


use Com\Pdd\Pop\Sdk\Api\Request\PddDdkCmsPromUrlGenerateRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkGoodsDetailRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkGoodsPromotionUrlGenerateRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkGoodsRecommendGetRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkGoodsSearchRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkMemberAuthorityQueryRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkOrderListIncrementGetRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkOrderListRangeGetRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkResourceUrlGenRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkRpPromUrlGenerateRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddGoodsCatsGetRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddGoodsOptGetRequest;
use Com\Pdd\Pop\Sdk\PopHttpClient;
use Com\Pdd\Pop\Sdk\PopHttpException;
use Jimphei\mallsdk\http\Request;
use Jimphei\mallsdk\util\RespTrait;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkOrderDetailGetRequest;

class Pdd implements RequestInterface
{

    use RespTrait;
    private $clientId;
    private $clientSecret;
    private $pid;
    private $startTime;
    private $endTime;
    private $client;
    public function __construct($clientId,$clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->client = new PopHttpClient($clientId, $clientSecret);
    }
    /**
     * @return mixed
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param mixed $pid
     */
    public function setPid($pid): void
    {
        $this->pid = $pid;
    }

    /**
     * 订单开始时间
     * @param int $startTime
     * @return mixed
     */
    public function from(int $startTime):self
    {
        $this->startTime = $startTime;
        return $this;
        // TODO: Implement from() method.
    }

    /**
     * 设置订单结束时间
     * @param int $endtime
     * @return mixed
     */
    public function to(int $endTime):self
    {
        $this->endTime = $endTime;
        return $this;
        // TODO: Implement to() method.
    }

    /**
     * 抓取订单
     * @param array $params
     * @param bool $return_count
     * @return mixed|void
     */
    public function fetchOrder($params = [], $return_count = true)
    {
        if(!$this->startTime or !$this->endTime){
            return $this->error(1,'缺少时间字段');
        }
        if(($this->endTime-$this->startTime)>24*3600){
            return $this->error(1,'时间间隔大于24小时');
        }

        $request = new PddDdkOrderListIncrementGetRequest();

        if(isset($params['is_gift'])){
            $request->setCashGiftOrder(true);
        }
        else{
            if(isset($params['query_order_type'])){
                $request->setQueryOrderType($params['query_order_type']);
            }
        }

        $request->setEndUpdateTime($this->endTime);
        if(isset($params['page'])){
            $request->setPage($params['page']);
        }
        if(isset($params['pageSize'])){
            $request->setPageSize($params['pageSize']);
        }
        $request->setReturnCount($return_count);
        $request->setStartUpdateTime($this->startTime);

        try{
            $response = $this->client->syncInvoke($request);

        } catch(PopHttpException $e){
            echo $e->getMessage();
            exit;
        }
        $content = $response->getContent();

        return $this->parse($content);
    }


    public function fetchOrderByPayTime($params = [], $return_count = true){
        if(!$this->startTime or !$this->endTime){
            return $this->error(1,'缺少时间字段');
        }
        if((strtotime($this->endTime)-strtotime($this->startTime))>24*3600){
            return $this->error(1,'时间间隔大于24小时');
        }
        $request = new PddDdkOrderListRangeGetRequest();

        if(isset($params['is_gift'])){
            $request->setCashGiftOrder(true);
        }
        else{
            if(isset($params['query_order_type'])){
                $request->setQueryOrderType($params['query_order_type']);
            }
        }

        if(isset($params['pageSize'])){
            $request->setPageSize($params['pageSize']);
        }

        $request->setStartTime(date('Y-m-d H:i:s',$this->startTime));

        $request->setEndTime(date('Y-m-d H:i:s',$this->endTime));
        try{
            $response = $this->client->syncInvoke($request);

        } catch(PopHttpException $e){
            echo $e->getMessage();
            exit;
        }
        $content = $response->getContent();

        return $this->parse($content);
    }

    /**
     * 查询订单详情
     * @param string $order_sn
     * @return mixed
     */
    public function getOrderDetail(string $order_sn)
    {
        $request = new PddDdkOrderDetailGetRequest();

        $request->setOrderSn($order_sn);
        $request->setQueryOrderType(1);
        try{
            $response = $this->client->syncInvoke($request);

        } catch(PopHttpException $e){
            echo $e->getMessage();
            exit;
        }
        $content = $response->getContent();

        return $this->parse($content);
    }

    /**
     * 生成连接 多多进宝推广链接生成
     * @param string $goods_id 加密大goods_id
     * @param array $custom_parameters 自定义参数
     * @return mixed
     */

    public function genLink($goods_id, array $custom_parameters,$params=[],$need_auth=false,$pid=null)
    {
        if(is_string($goods_id)){
            $goods_id = [$goods_id];
        }
        $request = new PddDdkGoodsPromotionUrlGenerateRequest();

        if(isset($params['cash_gift_id'])){
            $request->setCashGiftId($params['cash_gift_id']);
        }
        if(isset($params['cash_gift_name'])){
            $request->setCashGiftName($params['cash_gift_name']);
        }
        if(!array_key_exists('uid',$custom_parameters)){
            return $this->error(1,'缺少uid');
        }

        if($pid){
            $request->setPid($pid);
        }
        else{
            if(!$this->pid){
                return $this->error(1,'缺少pid');
            }
        }           
        $request->setCustomParameters(json_encode($custom_parameters,JSON_UNESCAPED_UNICODE));
        if($need_auth){
            $request->setGenerateAuthorityUrl(true);
        }

        $request->setGenerateMallCollectCoupon(true);
        $request->setGenerateQqApp(true);
        $request->setGenerateSchemaUrl(true);
        $request->setGenerateShortUrl(true);
        $request->setGenerateWeApp(true);
        $request->setGoodsSignList($goods_id);
        $request->setMultiGroup(true);
        $request->setPId($this->pid);
        if(isset($params['searchId'])){
            $request->setSearchId($params['searchId']);
        }
        try{
            $response = $this->client->syncInvoke($request);
        } catch(PopHttpException $e){
            echo $e->getMessage();
            exit;
        }
        $content = $response->getContent();
        return $this->parse($content);
    }

    /**
     * 频道推广连接
     * @param int $resource_type
     * @param array $custom_parameters
     * @return mixed|void
     */
    public function getResourceLink(int $resource_type, array $custom_parameters,string $url = '')
    {
        if(!$this->pid){
            return $this->error(1,'缺少pid');
        }

        if(!in_array('uid',$custom_parameters)){
            return $this->error(1,'缺少uid');
        }

        $request = new PddDdkResourceUrlGenRequest();

        $request->setCustomParameters(json_encode($custom_parameters,JSON_UNESCAPED_UNICODE));
        $request->setGenerateWeApp(true);
        $request->setPid($this->pid);
        $request->setResourceType($resource_type);
        if($url){
            $request->setUrl($url);
        }

        try{
            $response = $this->client->syncInvoke($request);
        } catch(PopHttpException $e){
            echo $e->getMessage();
            exit;
        }
        $content = $response->getContent();
        return $this->parse($content);
    }

    public function getGoodsOpts(int $parent_opt_id)
    {
        $request = new PddGoodsOptGetRequest();
        $request->setParentOptId($parent_opt_id);
        try{
            $response = $this->client->syncInvoke($request);

        } catch(PopHttpException $e){
            echo $e->getMessage();
            exit;
        }
        $content = $response->getContent();
        return $this->parse($content);
    }

    public function getGoodsCats(int $parent_cat_id)
    {
        $request = new PddGoodsCatsGetRequest();
        $request->setParentCatId($parent_cat_id);
        try{
            $response = $this->client->syncInvoke($request);

        } catch(PopHttpException $e){
            echo $e->getMessage();
            exit;
        }
        $content = $response->getContent();
        return $this->parse($content);
    }

    public function getChannelLink($p_id_list, array $custom_parameters, int $channel_type = 0)
    {
        if(!in_array('uid',$custom_parameters)){
            return $this->error(1,'缺少uid');
        }
        $request = new PddDdkCmsPromUrlGenerateRequest();
        $request->setChannelType($channel_type);
        if(is_string($p_id_list)){
            $p_id_list = [$p_id_list];
        }
        $request->setPIdList($p_id_list);
        $request->setCustomParameters(json_encode($custom_parameters,JSON_UNESCAPED_UNICODE));
        $request->setGenerateMobile(true);
        $request->setGenerateShortUrl(true);
        $request->setGenerateWeApp(true);
        $request->setGenerateSchemaUrl(true);
        try{
            $response = $this->client->syncInvoke($request);

        } catch(PopHttpException $e){
            echo $e->getMessage();
            exit;
        }
        $content = $response->getContent();

        return $this->parse($content);

    }

    public function getRecommendGoods(int $cat_id = null, int $channel_type = 1, array $activity_tags = [], string $uid = null, int $page = 1, int $pageSize = 20)
    {
        $request = new PddDdkGoodsRecommendGetRequest();
        $request->setCatId($cat_id);
        $request->setChannelType($channel_type);

        if(!empty($activity_tags)){
            $request->setActivityTags($activity_tags);
        }
        if(!empty($uid)){
            $custom = ['uid'=>$uid];
            $request->setCustomParameters(json_encode($custom,JSON_UNESCAPED_UNICODE));
        }
        $offset = ($page - 1)*$pageSize;
        $request->setOffset($offset);
        $request->setLimit($pageSize);
        try{
            $response = $this->client->syncInvoke($request);

        } catch(PopHttpException $e){
            echo $e->getMessage();
            exit;
        }
        $content = $response->getContent();

        return $this->parse($content);

    }

    public function goodsSearch($opt_id = null, $cat_id = null,$keyword = '', $sort_type = 5, $page = 1, $pageSize = 20, $with_coupon = false,$pid=null,$uid=0)
    {
        $request = new PddDdkGoodsSearchRequest();
        if($cat_id){
            $request->setCatId($cat_id);
        }
        if($opt_id){
            $request->setOptId($opt_id);
        }
        if($keyword){
            $request->setKeyword($keyword);
        }
        $request->setSortType($sort_type);
        $request->setPage($page);
        $request->setPageSize($pageSize);
        $request->setWithCoupon($with_coupon);
        if($pid){
            $request->setPid($pid);
        }
        $custom_parameters = ['uid'=>$uid];
        $request->setCustomParameters(json_encode($custom_parameters,JSON_UNESCAPED_UNICODE));        
        try{
            $response = $this->client->syncInvoke($request);

        } catch(PopHttpException $e){
            echo $e->getMessage();
            exit;
        }

        $content = $response->getContent();
        return $this->parse($content);
    }

    public function goodsDetail($goods_id,$uid = null,$pid = null)
    {
        $request = new PddDdkGoodsDetailRequest();
        if(is_numeric($goods_id)){
            $goods_id = $this->goodsIdToSign($goods_id);
        }
        $request->setGoodsSign($goods_id);
        if($uid){
            $custom_paramster = ['uid'=>$uid];
            $request->setCustomParameters(json_encode($custom_paramster));
        }
        if($pid){
            $request->setPid($pid);
        }

        try{
            $response = $this->client->syncInvoke($request);

        } catch(PopHttpException $e){
            echo $e->getMessage();
            exit;
        }
        $content = $response->getContent();
        return $this->parse($content);
    }

    public function queryIsAuthority(array $custom_parameters = [], string $pid = '')
    {
        $request = new PddDdkMemberAuthorityQueryRequest();
        if(!empty($custom_parameters)){
            $request->setCustomParameters(json_encode($custom_parameters,JSON_UNESCAPED_UNICODE));
        }

        if($pid){
            $request->setPid($pid);
        }
        try{
            $response = $this->client->syncInvoke($request);

        } catch(PopHttpException $e){
            echo $e->getMessage();
            exit;
        }

        $content = $response->getContent();
        return $this->parse($content);
    }

    public function getAuthorityLink($uid,$p_id_list)
    {

        $channel_type = 10;
        $request = new PddDdkRpPromUrlGenerateRequest();
        if(is_string($p_id_list)){
            $p_id_list = [$p_id_list];
        }

        $request->setPIdList($p_id_list);
        $request->setChannelType($channel_type);
        $custom = ['uid'=>$uid];
        $request->setCustomParameters(json_encode($custom));
        $request->setGenerateSchemaUrl(true);
        $request->setGenerateWeApp(true);
        $request->setGenerateShortUrl(true);

        try{
            $response = $this->client->syncInvoke($request);

        } catch(PopHttpException $e){
            echo $e->getMessage();
            exit;
        }

        $content = $response->getContent();
        return $this->parse($content);
    }

    public function goodsIdToSign(int $goods_id)
    {
        $request = new PddDdkGoodsSearchRequest();
        $request->setKeyword($goods_id);
        $request->setPid($this->pid);
        $custom = ['uid'=>1];
        $request->setCustomParameters(json_encode($custom));
        try{
            $response = $this->client->syncInvoke($request);
        } catch(PopHttpException $e){
            echo $e->getMessage();
            exit;
        }
        $content = $response->getContent();

        $ret = $this->parse($content);

        if($ret['errcode']){
            return $ret;
        }

        return $ret['data']['goods_list'][0]['goods_sign'];
    }


    public function parse(array $content){
        $ret = [
            'errcode'=>0
        ];
        if(isset($content['error_response'])){
            $ret['errcode'] = 1;
            $ret['msg'] = $content['error_response']['sub_msg'];
            return $ret;
        }

        $ret['data'] = reset($content);
        return $ret;
    }


}
