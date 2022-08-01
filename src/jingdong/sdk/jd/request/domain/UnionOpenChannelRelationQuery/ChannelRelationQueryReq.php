<?php
namespace UnionOpenChannelRelationQuery;
class ChannelRelationQueryReq{

    private $params=array();

    function __construct(){
        $this->params["@type"]="com.jd.union.open.gateway.api.dto.channel.relation.ChannelRelationQueryReq";
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
            
    private $channelId;
    
    public function setChannelId($channelId){
        $this->params['channelId'] = $channelId;
    }

    public function getChannelId(){
        return $this->channelId;
    }
            
    private $pin;
    
    public function setPin($pin){
        $this->params['pin'] = $pin;
    }

    public function getPin(){
        return $this->pin;
    }
    
    function getInstance(){
        return $this->params;
    }

}

?>