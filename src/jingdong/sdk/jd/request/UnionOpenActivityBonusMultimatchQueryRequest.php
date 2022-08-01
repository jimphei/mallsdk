<?php
class UnionOpenActivityBonusMultimatchQueryRequest
{

    public function __construct()
    {
         $this->version = "1.0";
    }

	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jd.union.open.activity.bonus.multimatch.query";
	}
	
	public function getApiParas(){
        if(empty($this->apiParas)){
	        return "{}";
	    }
		return json_encode($this->apiParas);
	}
	
	public function check(){
		
	}
	
    public function putOtherTextParam($key, $value){
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}

    private $version;

    public function setVersion($version){
        $this->version = $version;
    }

    public function getVersion(){
        return $this->version;
    }
    private  $batchUserMatchActivityRequest;

    public function setBatchUserMatchActivityRequest($batchUserMatchActivityRequest){
        $this->apiParas['batchUserMatchActivityRequest'] = $batchUserMatchActivityRequest;
    }
    public function getBatchUserMatchActivityRequest(){
        return $this->apiParas['batchUserMatchActivityRequest'];
    }
}

?>