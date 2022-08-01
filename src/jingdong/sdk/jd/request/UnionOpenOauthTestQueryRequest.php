<?php
class UnionOpenOauthTestQueryRequest
{

    public function __construct()
    {
         $this->version = "1.0";
    }

	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jd.union.open.oauth.test.query";
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
    private  $oauthTestReq;

    public function setOauthTestReq($oauthTestReq){
        $this->apiParas['oauthTestReq'] = $oauthTestReq;
    }
    public function getOauthTestReq(){
        return $this->apiParas['oauthTestReq'];
    }
}

?>