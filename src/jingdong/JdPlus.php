<?php
/**
 * 项目 TKSAAS
 * Author jimphei
 * Date 2020-06-04
 */
namespace Jimphei\mallsdk\jingdong;

use Jimphei\mallsdk\jingdong\request\domain\GoodsReqDTO;
use Jimphei\mallsdk\jingdong\request\UnionOpenGoodsQueryRequest;
use Jimphei\mallsdk\http\Request;

class JdPlus extends JingDong
{
    protected $plus_app_id;
    protected $plus_app_secret;

    protected $vekey;
    protected $unionId;
    protected $request;
    protected $authkey;
    const WY_URL = 'http://api.vephp.com/jd';///prombyuid?materialId=https://item.jd.com/67289511972.html&unionId=1001034551&positionId=1818437423'

    public function __construct($appKey = "", $appSecret = "",$vekey,$unionId)
    {
        parent::__construct($appKey, $appSecret);
        $this->vekey = $vekey;
        $this->unionId = $unionId;
        $this->request = new Request();
    }

    public function setPlusConfig($app_id,$app_secret){
        $this->plus_app_id = $app_id;
        $this->plus_app_secret = $app_secret;
    }

    public function setAuthkey($authkey){
        $this->authkey = $authkey;
    }
    //public function 

    protected function generateSignPlus($params)
    {
        ksort($params);
        $stringToBeSigned = $this->plus_app_secret;
        foreach ($params as $k => $v)
        {
            if("@" != substr($v, 0, 1))
            {
                $stringToBeSigned .= "$k$v";
            }
        }
        unset($k, $v);
        $stringToBeSigned .= $this->plus_app_secret;
        return strtoupper(md5($stringToBeSigned));
    }

    public function executePlus($request, $access_token = null)
    {
        //组装系统参数
        $sysParams["app_key"] = $this->plus_app_id;
        $version = $request->getVersion();

        $sysParams["v"] = empty($version)? $this->version:$version;
        $sysParams["method"] = $request->getApiMethodName();
        $sysParams["timestamp"] = $this->getCurrentTimeFormatted();
        if (null != $access_token)
        {
            $sysParams["access_token"] = $access_token;
        }

        //获取业务参数
        $apiParams = $request->getApiParas();
        //var_dump($apiParams);
        $sysParams[$this->json_param_key] = $apiParams;

        //签名
        $sysParams["sign"] = $this->generateSignPlus($sysParams);
        //系统参数放入GET请求串
        $requestUrl = $this->serverUrl . "?";
        foreach ($sysParams as $sysParamKey => $sysParamValue)
        {
            $requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
        }
        //发起HTTP请求
        try
        {
            $resp = $this->curl($requestUrl, $apiParams);
        }
        catch (Exception $e)
        {
            $result = new stdClass();
            $result->code = $e->getCode();
            $result->msg = $e->getMessage();
            return $result;
        }

        //解析JD返回结果
        $respWellFormed = false;
        if ("json" == $this->format)
        {
            $respObject = json_decode($resp,true);

            if (null !== $respObject)
            {
                $respWellFormed = true;
                if(!empty($respObject['error_response'])){
                    return $respObject['error_response'];
                }
                //var_dump($respObject);exit;
                $key = str_replace('.','_',$sysParams["method"]).'_responce';
                return json_decode($respObject[$key]['queryResult'],true);
            }
        }
        else if("xml" == $this->format)
        {
            $respObject = @simplexml_load_string($resp);
            if (false !== $respObject)
            {
                $respWellFormed = true;
            }
        }

        //返回的HTTP文本不是标准JSON或者XML，记下错误日志
        if (false === $respWellFormed)
        {
            $result = new stdClass();
            $result->code = 1;
            $result->msg = "HTTP_RESPONSE_NOT_WELL_FORMED";
            return $result;
        }

        return $respObject;
    }


    /**
     * 关键词查询选品
     * @param number $cat1Id:一级类目
     * @param number $cat2Id:二级类目
     * @param number $cat3Id:三级类目
     * @param string $keyword:关键词
     * @param number $page_index:页码
     * @param number $page_size:每页数量
     * @param string $sort_name:排序字段[pcPrice pc价],[pcCommission pc佣金],[pcCommissionShare pc佣金比例],[inOrderCount30Days 30天引入订单量],[inOrderComm30Days 30天支出佣金]
     * @param string $sort:	asc,desc升降序,默认降序
     * @return Ambigous <unknown, mixed>
     */
    public function searchGoods($cat1Id=null,$cat2Id=null,$cat3Id=null,$keyword='',$page_index=1,$page_size=10,$sort_name='inOrderComm30Days',$sort='desc')
    {
        $req = new UnionOpenGoodsQueryRequest();

        $goodsReqDTO= new GoodsReqDTO();

        $cat1Id && $goodsReqDTO->setCid1($cat1Id);
        $cat2Id && $goodsReqDTO->setCid2($cat2Id);
        $cat3Id && $goodsReqDTO->setCid3($cat3Id);
        $goodsReqDTO->setKeyword($keyword);
        $goodsReqDTO->setPageSize($page_size);
        $goodsReqDTO->setPageIndex($page_index);
        $goodsReqDTO->setSortName($sort_name);
        $goodsReqDTO->setSort($sort);
        $goodsReqDTO->setIsCoupon(1);

        $req->setGoodsReqDTO($goodsReqDTO);
        $req->setVersion("1.0");
        return $this->executePlus($req);
    }

    public function getPromotion($goodsId,$positionId,$siteId,$couponLink = ''){
        $url = self::WY_URL.'/prombyuid';
        $materialId = 'https://item.jd.com/'.$goodsId.'.html';
        $data = [
            'vekey'=>$this->vekey,
            'materialId'=>$materialId,
            'unionId'=>$this->unionId,
            'positionId'=>$positionId,
            'siteId'=>$siteId,
        ];
        if(!empty($couponLink)){
            $data['couponUrl'] = $couponLink;
        }
        $result = $this->request->get($url,$data);
        return $result->array();
    }

    public function getUidPromotion($goodsId,$positionId,$couponLink = ''){
        $url = self::WY_URL.'/prombyuid';
        $materialId = 'https://item.jd.com/'.$goodsId.'.html';
        $data = [
            'vekey'=>$this->vekey,
            'materialId'=>$materialId,
            'unionId'=>$this->unionId,
            'positionId'=>$positionId
        ];
        if(!empty($couponLink)){
            $data['couponUrl'] = $couponLink;
        }
        $result = $this->request->get($url,$data);
        return $result->array();
    }

    public function getPid($uid,$siteId){
        $url = self::WY_URL.'/createpid';
        $data = [
            'vekey'=>$this->vekey,
            'key'=>$this->authkey,
            'unionId'=>$this->unionId,
            'unionType'=>3,
            'type'=>2,
            'spaceNameList'=>'pid_for_'.$uid,
            'siteId'=>$siteId
        ];

        $result = $this->request->get($url,$data);
        return $result->array();
    }


}