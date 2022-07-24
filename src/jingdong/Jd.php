<?php


namespace Jimphei\mallsdk\jingdong;

include 'sdk/JdSdk.php';

class Jd
{
    use RequestTrait;
    const DDX_URL = 'http://api.tbk.dingdanxia.com';
    private static $instance;
    protected $appKey;
    protected $appSecret;
    protected $client;
    protected $accessToken;
    protected $unionId;
    
    protected $ddxApiKey;//订单侠key


    public function __construct($appKey = "", $appSecret = "")
    {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        $this->init();
    }

    public function setConfig($appKey,$appSecret){
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        $this->init();
    }

    private function init(){
        $this->client = new \JdClient();
        $this->client->appKey = $this->appKey;
        $this->client->appSecret = $this->appSecret;
    }

    /**
     * 单例获取当前对象
     * @Author: niugengyun
     * @Date: 2018/4/26
     * @return static
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * 魔术方法 调用不存在的静态方法时触发
     * @Author: niugengyun
     * @Date: 2018/4/26
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        $obj = self::getInstance ();
        return $obj->$name($arguments);
    }

    private function query($request,$accessToken=null){

        return $this->client->execute($request,$accessToken);
    }


    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param mixed $accessToken
     */
    public function setAccessToken($accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return mixed
     */
    public function getUnionId()
    {
        return $this->unionId;
    }

    /**
     * @param mixed $unionId
     */
    public function setUnionId($unionId): void
    {
        $this->unionId = $unionId;
    }
    /**
     * @return mixed
     */
    public function getDdxApiKey()
    {
        return $this->ddxApiKey;
    }

    /**
     * @param mixed $ddxApiKey
     */
    public function setDdxApiKey($ddxApiKey): void
    {
        $this->ddxApiKey = $ddxApiKey;
    }
    /**
     * 商品类目查询
     * @param number $parent_id:父类目id(一级父类目为0)
     * @param number $grade:类目级别 0，1，2 代表一、二、三级类目
     */
    public function searchGoodsCategory($parent_id=0,$grade=0)
    {
        $request = new \UnionOpenCategoryGoodsGetRequest();
        $req = new \UnionOpenCategoryGoodsGet\Req();
        $req->setParentId($parent_id);
        $req->setGrade($grade);
        $request->setReq($req);
        return $this->query($request);
    }

    /**
     * @param int $pindao 频道，也就是分类吧
     * @param int $page  第几页
     * @param int $pageSize 每页大小
     * @param string $order 排序字段
     * @param string $sort 排序方式
     * @param string $pid 用户推广位，做订单归属有用
     * @return mixed
     */
    public function queryGoods(int $pindao,$page=0,$pageSize=10,$order='inOrderCount30DaysSku',$sort='desc',$pid='')
    {
        $req =new \UnionOpenGoodsMaterialQuery\GoodsReq;
        $req->setEliteId($pindao);
        $req->setHasCoupon(true);
        $req->setPageIndex($page);
        $req->setPageSize($pageSize);
        $req->setSortName($order);
        $req->setSort($sort);
        if(!empty($pid)){
            $req->setPid($pid);
        }
        $request = new \UnionOpenGoodsMaterialQueryRequest();
        $request->setGoodsReq($req);
        return $this->query($request);

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
    public function searchGoods($cat1Id='',$cat2Id='',$cat3Id='',$keyword='',$page_index=1,$page_size=10,$sort_name='',$sort='desc')
    {
        $url = self::DDX_URL.'/jd/query_goods';
        $data['apikey'] = $this->getDdxApiKey();
        $data['cid1'] = $cat1Id;
        $data['cid2'] = $cat2Id;
        $data['cid3'] = $cat3Id;
        $data['keyword'] = $keyword;
        $data['pageIndex'] = $page_index;
        $data['pageSize'] = $page_size;
        $data['sortName'] = $sort_name;
        $data['sort'] = $sort;
        return $this->curl_post($url,$data);
    }

    /**
     * 订单查询
     * @param $start_time 开始时间
     * @param $end_time 结束时间
     * @param int $page 页码
     * @param int $pageSize 页码大小
     * @param int $type 查询方式：1 下单时间 2，完成时间（购买用户确认收货时间），3 更新时间
     * @return mixed
     */
    public function queryOrders($start_time,$end_time,int $page=1,$pageSize=500,int $type=3){
        $request = new \UnionOpenOrderRowQueryRequest();
        $req = new \UnionOpenOrderRowQuery\OrderReq();
        $req->setStartTime($start_time);
        $req->setEndTime($end_time);
        $req->setPageIndex($page);
        $req->setPageSize($pageSize);
        $req->setType($type);
        $request->setOrderReq($req);
        return $this->query($request);
    }

    //新版联盟订单查询
    public function queryOpenOrders($time,$page,$pageSize, $query_type){
        //$queryDate = '2019120915';
        $serverUrl='https://router.jd.com/api';
        $params_yewu = [
            'orderReq' =>[
                'time'=>$time,
                'pageNo'=>strval($page),
                'pageSize'=>strval($pageSize),
                'type'=>$query_type
            ]
        ];
        $param_json =json_encode($params_yewu);
        $params = [
            'v'=>'1.0',
            'method'=>'jd.union.open.order.query',
            'access_token'=>'',
            'app_key'=>$this->appkey,
            'sign_method'=>'md5',
            'format'=>'json',
            'timestamp'=>date('Y-m-d H:i:s'),
            'param_json'=>$param_json
        ];

        $params['sign'] = $this->genSign($params);

        $finalUrl=$this->serverUrl.'?'.http_build_query($params);
        $result = file_get_contents($finalUrl);
        $res = json_decode($result,true);

        return json_decode($res['jd_union_open_order_query_response']['result'],true);
    }

    //新版联盟订单查询2
    // 2020-01-28 京东联盟更改接口
    public function queryOpenOrders2($startTime,$endTime,$page,$pageSize, $query_type){
        $params_yewu = array(
            'orderReq' => array(
                'startTime'=>$startTime,
                'endTime'=>$endTime,
                'pageIndex'=>strval($page),
                'pageSize'=>strval($pageSize),
                'type'=>$query_type
            )
        );
        $param_json =json_encode($params_yewu);
        $params = array(
            'v'=>'1.0',
            'method'=>'jd.union.open.order.row.query',
            'access_token'=>'',
            'app_key'=>$this->appkey,
            'sign_method'=>'md5',
            'format'=>'json',
            'timestamp'=>date('Y-m-d H:i:s'),
            'param_json'=>$param_json
        );

        $params['sign'] = $this->genSign($params);

        $finalUrl=$this->serverUrl.'?'.http_build_query($params);
        $result = file_get_contents($finalUrl);
        $res = json_decode($result,true);

        return json_decode($res['jd_union_open_order_row_query_response']['result'],true);
    }

    public function genSign($params){
        ksort($params);
        $queryString = "";
        foreach($params as $k=>$val)
        {
            if(!empty($val)){
                $queryString.=$k.$val;
            }
        }

        $queryString = $this->appSecret.$queryString.$this->appSecret;

        //$md5str = $this->encodeHexString($this->md5Hex($queryString));

        $sign = strtoupper(md5($queryString));
        return $sign;
    }

    public function getGoodsInfo($skuId){
        $serverUrl='https://router.jd.com/api';
        $params_yewu = [
            'skuIds' => $skuId
        ];
        $param_json =json_encode($params_yewu);
        $params = [
            'v'=>'1.0',
            'method'=>'jd.union.open.goods.promotiongoodsinfo.query',
            'access_token'=>'',
            'app_key'=>$this->appkey,
            'sign_method'=>'md5',
            'format'=>'json',
            'timestamp'=>date('Y-m-d H:i:s'),
            'param_json'=>$param_json
        ];

        $params['sign'] = $this->genSign($params);

        $finalUrl=$this->serverUrl.'?'.http_build_query($params);
        $result = file_get_contents($finalUrl);

        $res = json_decode($result,true);

        return json_decode($res['jd_union_open_goods_promotiongoodsinfo_query_response']['result'],true);
    }

    public function queryGoodsInfo($skuId){
        $request = new \UnionOpenGoodsBigfieldQueryRequest();
        $req = new \UnionOpenGoodsBigfieldQuery\GoodsReq();
        $skus =[$skuId];
        $req->setSkuIds($skus);
        $request->setGoodsReq($req);
        return $this->query($request);
    }







}