<?php
namespace UnionOpenGoodsCombinationQuery;
class GoodsReq{

    private $params=array();

    function __construct(){
        $this->params["@type"]="com.jd.union.open.gateway.api.dto.goods.combination.CombinationGoodsReq";
    }
        
    private $batchId;
    
    public function setBatchId($batchId){
        $this->params['batchId'] = $batchId;
    }

    public function getBatchId(){
        return $this->batchId;
    }
            
    private $needClickUrl;
    
    public function setNeedClickUrl($needClickUrl){
        $this->params['needClickUrl'] = $needClickUrl;
    }

    public function getNeedClickUrl(){
        return $this->needClickUrl;
    }
            
    private $positionId;
    
    public function setPositionId($positionId){
        $this->params['positionId'] = $positionId;
    }

    public function getPositionId(){
        return $this->positionId;
    }
            
    private $pid;
    
    public function setPid($pid){
        $this->params['pid'] = $pid;
    }

    public function getPid(){
        return $this->pid;
    }
            
    private $subUnionId;
    
    public function setSubUnionId($subUnionId){
        $this->params['subUnionId'] = $subUnionId;
    }

    public function getSubUnionId(){
        return $this->subUnionId;
    }
            
    private $channelId;
    
    public function setChannelId($channelId){
        $this->params['channelId'] = $channelId;
    }

    public function getChannelId(){
        return $this->channelId;
    }
            
    private $ext;
    
    public function setExt($ext){
        $this->params['ext'] = $ext;
    }

    public function getExt(){
        return $this->ext;
    }
            
    private $pageIndex;
    
    public function setPageIndex($pageIndex){
        $this->params['pageIndex'] = $pageIndex;
    }

    public function getPageIndex(){
        return $this->pageIndex;
    }
            
    private $pageSize;
    
    public function setPageSize($pageSize){
        $this->params['pageSize'] = $pageSize;
    }

    public function getPageSize(){
        return $this->pageSize;
    }
    
    function getInstance(){
        return $this->params;
    }

}

?>