<?php
class StockStateAreaStockStateExportRequest
{


	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.stock.state.areaStockStateExport";
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
                                                        		                                    	                   			private $buId;
    	                        
	public function setBuId($buId){
		$this->buId = $buId;
         $this->apiParas["buId"] = $buId;
	}

	public function getBuId(){
	  return $this->buId;
	}

                        	                   			private $useDefaultTenant;
    	                        
	public function setUseDefaultTenant($useDefaultTenant){
		$this->useDefaultTenant = $useDefaultTenant;
         $this->apiParas["useDefaultTenant"] = $useDefaultTenant;
	}

	public function getUseDefaultTenant(){
	  return $this->useDefaultTenant;
	}

                        	                   			private $systemName;
    	                        
	public function setSystemName($systemName){
		$this->systemName = $systemName;
         $this->apiParas["systemName"] = $systemName;
	}

	public function getSystemName(){
	  return $this->systemName;
	}

                        	                   			private $timezone;
    	                        
	public function setTimezone($timezone){
		$this->timezone = $timezone;
         $this->apiParas["timezone"] = $timezone;
	}

	public function getTimezone(){
	  return $this->timezone;
	}

                        	                   			private $nationId;
    	                        
	public function setNationId($nationId){
		$this->nationId = $nationId;
         $this->apiParas["nationId"] = $nationId;
	}

	public function getNationId(){
	  return $this->nationId;
	}

                        	                   			private $skuTenant;
    	                        
	public function setSkuTenant($skuTenant){
		$this->skuTenant = $skuTenant;
         $this->apiParas["skuTenant"] = $skuTenant;
	}

	public function getSkuTenant(){
	  return $this->skuTenant;
	}

                        	                   			private $sysIp;
    	                        
	public function setSysIp($sysIp){
		$this->sysIp = $sysIp;
         $this->apiParas["sysIp"] = $sysIp;
	}

	public function getSysIp(){
	  return $this->sysIp;
	}

                        	                   			private $language;
    	                        
	public function setLanguage($language){
		$this->language = $language;
         $this->apiParas["language"] = $language;
	}

	public function getLanguage(){
	  return $this->language;
	}

                        	                   			private $tenantId;
    	                        
	public function setTenantId($tenantId){
		$this->tenantId = $tenantId;
         $this->apiParas["tenantId"] = $tenantId;
	}

	public function getTenantId(){
	  return $this->tenantId;
	}

                                            		                                    	                   			private $CallerParamKey;
    	                        
	public function setCallerParamKey($CallerParamKey){
		$this->CallerParamKey = $CallerParamKey;
         $this->apiParas["CallerParamKey"] = $CallerParamKey;
	}

	public function getCallerParamKey(){
	  return $this->CallerParamKey;
	}

                        	                   			private $CallerParamValue;
    	                        
	public function setCallerParamValue($CallerParamValue){
		$this->CallerParamValue = $CallerParamValue;
         $this->apiParas["CallerParamValue"] = $CallerParamValue;
	}

	public function getCallerParamValue(){
	  return $this->CallerParamValue;
	}

                                                                        		                                    	                   			private $tENANTID;
    	                                                            
	public function setTENANTID($tENANTID){
		$this->tENANTID = $tENANTID;
         $this->apiParas["TENANT_ID"] = $tENANTID;
	}

	public function getTENANTID(){
	  return $this->tENANTID;
	}

                        	                   			private $bUID;
    	                                                            
	public function setBUID($bUID){
		$this->bUID = $bUID;
         $this->apiParas["BU_ID"] = $bUID;
	}

	public function getBUID(){
	  return $this->bUID;
	}

                        	                   			private $cHANNELID;
    	                                                            
	public function setCHANNELID($cHANNELID){
		$this->cHANNELID = $cHANNELID;
         $this->apiParas["CHANNEL_ID"] = $cHANNELID;
	}

	public function getCHANNELID(){
	  return $this->cHANNELID;
	}

                        	                   			private $UA;
    	                        
	public function setUA($UA){
		$this->UA = $UA;
         $this->apiParas["UA"] = $UA;
	}

	public function getUA(){
	  return $this->UA;
	}

                                                                                                    		                                                             	                        	                                                                                                                                                                                                                                                                                        private $num;
                              public function setNum($num ){
                 $this->num=$num;
                 $this->apiParas["num"] = $num;
              }

              public function getNum(){
              	return $this->num;
              }
                                                                                                                                                                                                                                                                                                                       private $skuId;
                              public function setSkuId($skuId ){
                 $this->skuId=$skuId;
                 $this->apiParas["skuId"] = $skuId;
              }

              public function getSkuId(){
              	return $this->skuId;
              }
                                                                                                                                        	                   			private $channelId;
    	                        
	public function setChannelId($channelId){
		$this->channelId = $channelId;
         $this->apiParas["channelId"] = $channelId;
	}

	public function getChannelId(){
	  return $this->channelId;
	}

                                            		                                    	                   			private $stateId;
    	                        
	public function setStateId($stateId){
		$this->stateId = $stateId;
         $this->apiParas["stateId"] = $stateId;
	}

	public function getStateId(){
	  return $this->stateId;
	}

                        	                   			private $provinceId;
    	                        
	public function setProvinceId($provinceId){
		$this->provinceId = $provinceId;
         $this->apiParas["provinceId"] = $provinceId;
	}

	public function getProvinceId(){
	  return $this->provinceId;
	}

                        	                   			private $cityId;
    	                        
	public function setCityId($cityId){
		$this->cityId = $cityId;
         $this->apiParas["cityId"] = $cityId;
	}

	public function getCityId(){
	  return $this->cityId;
	}

                        	                   			private $countyId;
    	                        
	public function setCountyId($countyId){
		$this->countyId = $countyId;
         $this->apiParas["countyId"] = $countyId;
	}

	public function getCountyId(){
	  return $this->countyId;
	}

                        	                   			private $townId;
    	                        
	public function setTownId($townId){
		$this->townId = $townId;
         $this->apiParas["townId"] = $townId;
	}

	public function getTownId(){
	  return $this->townId;
	}

                                                                        		                                    	                   			private $longtitude;
    	                        
	public function setLongtitude($longtitude){
		$this->longtitude = $longtitude;
         $this->apiParas["longtitude"] = $longtitude;
	}

	public function getLongtitude(){
	  return $this->longtitude;
	}

                        	                   			private $latitude;
    	                        
	public function setLatitude($latitude){
		$this->latitude = $latitude;
         $this->apiParas["latitude"] = $latitude;
	}

	public function getLatitude(){
	  return $this->latitude;
	}

                        	                   			private $coordType;
    	                        
	public function setCoordType($coordType){
		$this->coordType = $coordType;
         $this->apiParas["coordType"] = $coordType;
	}

	public function getCoordType(){
	  return $this->coordType;
	}

                                                                        		                                    	                   			private $AreaStockStateSpuGlobalParamkey;
    	                        
	public function setAreaStockStateSpuGlobalParamkey($AreaStockStateSpuGlobalParamkey){
		$this->AreaStockStateSpuGlobalParamkey = $AreaStockStateSpuGlobalParamkey;
         $this->apiParas["AreaStockStateSpuGlobalParamkey"] = $AreaStockStateSpuGlobalParamkey;
	}

	public function getAreaStockStateSpuGlobalParamkey(){
	  return $this->AreaStockStateSpuGlobalParamkey;
	}

                        	                   			private $AreaStockStateSpuGlobalParamvalue;
    	                        
	public function setAreaStockStateSpuGlobalParamvalue($AreaStockStateSpuGlobalParamvalue){
		$this->AreaStockStateSpuGlobalParamvalue = $AreaStockStateSpuGlobalParamvalue;
         $this->apiParas["AreaStockStateSpuGlobalParamvalue"] = $AreaStockStateSpuGlobalParamvalue;
	}

	public function getAreaStockStateSpuGlobalParamvalue(){
	  return $this->AreaStockStateSpuGlobalParamvalue;
	}

                                                        }





        
 

