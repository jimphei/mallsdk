<?php
namespace UnionOpenActivityBonusMatchQuery;
class UserMatchActivityRequest{

    private $params=array();

    function __construct(){
        $this->params["@type"]="com.jd.union.open.gateway.api.dto.activity.bonus.UserMatchActivityRequest";
    }
        
    private $userIdType;
    
    public function setUserIdType($userIdType){
        $this->params['userIdType'] = $userIdType;
    }

    public function getUserIdType(){
        return $this->userIdType;
    }
            
    private $userId;
    
    public function setUserId($userId){
        $this->params['userId'] = $userId;
    }

    public function getUserId(){
        return $this->userId;
    }
    
    function getInstance(){
        return $this->params;
    }

}

?>