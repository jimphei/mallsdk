<?php
namespace UnionOpenActivityBonusMultimatchQuery;
class BatchUserMatchActivityRequest{

    private $params=array();

    function __construct(){
        $this->params["@type"]="com.jd.union.open.gateway.api.dto.activity.bonus.BatchUserMatchActivityRequest";
    }
        
    private $userIdType;
    
    public function setUserIdType($userIdType){
        $this->params['userIdType'] = $userIdType;
    }

    public function getUserIdType(){
        return $this->userIdType;
    }
            
    private $userIds;
    
    public function setUserIds($userIds){
        $this->params['userIds'] = $userIds;
    }

    public function getUserIds(){
        return $this->userIds;
    }
    
    function getInstance(){
        return $this->params;
    }

}

?>