<?php
namespace UnionOpenCartAdd;
class AddCartReq{

    private $params=array();

    function __construct(){
        $this->params["@type"]="com.jd.union.open.gateway.api.dto.cart.AddCartReq";
    }
        
    private $xid;
    
    public function setXid($xid){
        $this->params['xid'] = $xid;
    }

    public function getXid(){
        return $this->xid;
    }
            
    private $materialId;
    
    public function setMaterialId($materialId){
        $this->params['materialId'] = $materialId;
    }

    public function getMaterialId(){
        return $this->materialId;
    }
            
    private $skuId;
    
    public function setSkuId($skuId){
        $this->params['skuId'] = $skuId;
    }

    public function getSkuId(){
        return $this->skuId;
    }
            
    private $ip;
    
    public function setIp($ip){
        $this->params['ip'] = $ip;
    }

    public function getIp(){
        return $this->ip;
    }
            
    private $deviceId;
    
    public function setDeviceId($deviceId){
        $this->params['deviceId'] = $deviceId;
    }

    public function getDeviceId(){
        return $this->deviceId;
    }
            
    private $ua;
    
    public function setUa($ua){
        $this->params['ua'] = $ua;
    }

    public function getUa(){
        return $this->ua;
    }
            
    private $refer;
    
    public function setRefer($refer){
        $this->params['refer'] = $refer;
    }

    public function getRefer(){
        return $this->refer;
    }
    
    function getInstance(){
        return $this->params;
    }

}

?>