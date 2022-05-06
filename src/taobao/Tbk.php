<?php
/**
 * Created by PhpStorm.
 * User: jimphei
 * Date: 2021-06-07
 * Time: 16:51
 */

namespace Jimphei\mallsdk\taobao;

use Jimphei\mallsdk\util\RespTrait;

class Tbk implements RequestInterface
{
    use RespTrait;
    private $appKey;
    private $appSecret;
    private $format = "json";
    private $signMethod = "md5";
    private $pid;
    private $adzoneId;
    private $startTime;
    private $endTime;
    protected $client;

    public function __construct($appKey = "", $appSecret = "")
    {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        self::installSdk();
        $this->client = new \TopClient;
        $this->client->appkey = $this->appKey;
        $this->client->secretKey = $this->appSecret;
        $this->client->format = 'json';        
    }

    public static function installSdk(){
        if (!defined("TOP_AUTOLOADER_PATH"))
        {        
            $path = __DIR__."/topsdk/TopSdk.php";
            include($path);
        }
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
    public function setPid(string $pid): self
    {
        $this->pid = $pid;
        return $this;
    }
    /**
     * 获取媒体ID
     * @return mixed
     */
    public function getAdzoneId()
    {
        return $this->adzoneId;
    }
    /**
     * 设置媒体id
     * @param mixed $adzoneId
     */
    public function setAdzoneId($adzoneId): self
    {
        if(is_numeric($adzoneId)){
            $this->adzoneId = $adzoneId;
        }
        else{
            [$a,$b,$c,$zoneId] = explode("_",$adzoneId);
            $this->adzoneId = $zoneId;
        }
        return $this;
    }


    /**
     * 官方活动转链
     * @param int $act_id
     * @param int $relationId
     * @return mixed
     */
    public function activityLink(int $act_id, int $relationId = 0,$uid = '')
    {
        if(!$this->adzoneId){
            return $this->error(1,'缺少媒体ID');
        }
        $req = new \TbkActivityInfoGetRequest;

        $req->setAdzoneId($this->adzoneId);
        if($this->pid){
            $req->setSubPid($this->pid);
        }

        if(!empty($this->rid)){
            $req->setRelationId($this->rid);
        }
        if(!empty($relationId)){
            $req->setRelationId($relationId);
        }
        $req->setActivityMaterialId($act_id);

        $req->setUnionId($uid);
        $resp = $this->client->execute($req);

        return $resp;
    }

    /**
     * 生成口令
     * @param string $text
     * @param string $url
     * @return mixed
     */
    public function createKouling(string $text, string $url)
    {
        $req = new \TbkTpwdCreateRequest;
        $req->setText($text);
        $req->setUrl($url);
        $resp = $this->client->execute($req);
        return $resp;
    }

    /**
     * 订单开始实际
     * @param string $start_time
     * @return mixed
     */
    public function from(string $start_time): self
    {
        $this->startTime = $start_time;
        return $this;
        // TODO: Implement from() method.
    }

    /**
     * 订单结束时间
     * @param string $end_time
     * @return mixed
     */
    public function to(string $end_time): self
    {
        $this->endTime = $end_time;
        return $this;
        // TODO: Implement to() method.
    }

    /**
     * 抓取订单
     * @param $params
     * @param int $query_type
     * @param string $tk_status
     * @param string $order_scene
     * @return mixed
     */
    public function fetchOrder($params, int $query_type = 2, int $tk_status = null, int $order_scene = 1)
    {
        if(!$this->startTime or !$this->endTime){
            return $this->error(1,'缺少时间字段');
        }
        if((strtotime($this->endTime)-strtotime($this->startTime))>3*3600){
            return $this->error(1,'时间间隔大于3小时');
        }
        $req = new \TbkOrderDetailsGetRequest;
        $req->setStartTime($this->startTime);
        $req->setEndTime($this->endTime);
        if(!empty($params['page_no'])){
            $req->setPageNo($params['page_no']);
        }
        if(!empty($params['position_index'])){
            $req->setPositionIndex($params['position_index']);
        }
        $req->setPageSize($params['page_size']);
        $req->setQueryType($query_type);
        if(!empty($tk_status)){
            $req->setTkStatus($tk_status);
        }
        $req->setOrderScene($order_scene);
        $resp = $this->client->execute($req);
        return $resp;
    }

    /**
     * 备案
     * @param string $inviter_code
     * @param string $note
     * @return false|mixed|\ResultSet|\SimpleXMLElement|string
     */
    public function beian(string $inviter_code,$note = ''){
        $req = new \TbkScPublisherInfoSaveRequest;
        $req->setRelationFrom("1");
        $req->setOfflineScene("1");
        $req->setOnlineScene("1");
        $req->setInviterCode($inviter_code);
        $req->setInfoType("1");
        if($note){
            $req->setNote($note);
        }
        $resp = $this->client->execute($req);
        return $resp;
    }

    /**
     * 获取邀请码
     * @return false|mixed|\ResultSet|\SimpleXMLElement|string
     */
    public function genInviteCode(){
        $req = new \TbkScInvitecodeGetRequest;
        $req->setRelationApp("common");
        $req->setCodeType("1");
        $resp = $this->client->execute($req);  
        return $resp;
    }

    public function convertUrl(array $num_iids){
        $req = new \TbkItemConvertRequest()
    }


}
