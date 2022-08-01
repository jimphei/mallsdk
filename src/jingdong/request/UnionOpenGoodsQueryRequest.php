<?php

namespace Jimphei\mallsdk\jingdong\request;

class UnionOpenGoodsQueryRequest
{
    private $version;
    private $apiParas = array();
    private $goodsReqDTO;

    public function __construct()
    {
         $this->version = "1.0";
    }

    public function setParam($key,$value){
        $this->apiParas[$key] = $value;
    }

	public function getApiMethodName(){
	  return "jd.union.open.goods.query";
	}
	
	public function getApiParas(){
        if(empty($this->apiParas)){
	        return "{}";
	    }
		return json_encode($this->apiParas);
	}



    public function setVersion($version){
        $this->version = $version;
    }

    public function getVersion(){
        return $this->version;
    }

    public function setGoodsReqDTO($goodsReqDTO){
        $this->apiParas['goodsReqDTO'] = $goodsReqDTO;
    }

    public function getGoodsReqDTO(){
        return $this->apiParas['goodsReqDTO'];
    }

}



?>