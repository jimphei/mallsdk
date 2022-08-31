<?php


namespace Jimphei\mallsdk\taobao;


use Jimphei\mallsdk\http\Request;

class TbkPlus extends Tbk
{
    protected $wykey;
    protected $request;
    protected $url_wy = 'http://api.vephp.com';

    public function __construct($appKey = "", $appSecret = "",$wykey = "")
    {
        parent::__construct($appKey, $appSecret);
        $this->wykey = $wykey;
        $this->request = new Request();

    }

    //获取详情，高级版
    public function getItemInfoDetail($num_iid,$pid,$relationId=''){
        $info = $this->getItemInfo($num_iid);
        if($info['code']==0) {
            //获取卖点信息
            $info['data']['item_description']='';

            //商品折扣价格，保留2位小数，四舍五不入
            $info['data']['zk_final_price']=substr(sprintf("%.3f",$info['data']['zk_final_price']),0,-1);
            //查询商品佣金以及优惠券
            $num_iid=$info['data']['num_iid'];
            $url_gy=$this->url_wy."/hcapi?vekey={$this->wykey}&para={$num_iid}&pid={$pid}";
            $response = $this->request->get($url_gy);
        
            $result_gy = $response->array();

            $gy_data=$result_gy['data'];

            if(!empty($relationId)){
                $url_gy .= "&relationId={$relationId}";
                $response = $this->request->get($url_gy);
                $result_gy_r=$response->array();
                //var_dump($result_gy_r);exit;
                $gy_data_r=$result_gy_r['data'];
                $info['data']['coupon_click_url_r']=$gy_data_r['coupon_click_url'];
                $gy_data['coupon_click_url'] = $gy_data_r['coupon_click_url'];
            }
            else{
                $info['data']['coupon_click_url_r']='';
            }


            //优惠券总量
            if(isset($gy_data['coupon_total_count'])) {
                $info['data']['coupon_total_count']=$gy_data['coupon_total_count'];
            }else {
                $info['data']['coupon_total_count']=0;
            }
            //优惠券剩余量
            if(isset($gy_data['coupon_remain_count'])) {
                $info['data']['coupon_remain_count']=$gy_data['coupon_remain_count'];
            }else {
                $info['data']['coupon_remain_count']=0;
            }
            //优惠券开始时间
            if(isset($gy_data['coupon_start_time'])) {
                $info['data']['coupon_start_time']=$gy_data['coupon_start_time'];
            }else {
                $info['data']['coupon_start_time']='';
            }
            //优惠券结束时间
            if(isset($gy_data['coupon_end_time'])) {
                $info['data']['coupon_end_time']=$gy_data['coupon_end_time'];
            }else {
                $info['data']['coupon_end_time']='';
            }
            //优惠券信息
            if(isset($gy_data['coupon_info'])) {
                $info['data']['coupon_info']=$gy_data['coupon_info'];
                //优惠券面额
                $pos1=strpos($gy_data['coupon_info'],'减');
                $pos2=strripos($gy_data['coupon_info'],'元');
                $info['data']['coupon_amount']=substr($gy_data['coupon_info'], $pos1+3,$pos2-$pos1-3);
            }else {
                $info['data']['coupon_info']='';
                $info['data']['coupon_amount']=0;
            }
            //优惠券推广链接-默认链接地址，有优惠券的情况下会进行替换
            if(isset($gy_data['coupon_click_url'])) {
                $info['data']['coupon_click_url']=$gy_data['coupon_click_url'];
            }else {
                $info['data']['coupon_click_url']="https://uland.taobao.com/coupon/edetail?itemId=$num_iid&pid=$pid";
            }
            //佣金比率(%)
            //维易淘客
            $info['data']['commission_rate']=$gy_data['commission_rate'];
            //上海淘客
            //$res_info['data']['commission_rate']=$gy_data['max_commission_rate'];
            //佣金
            $info['data']['commission']=($info['data']['zk_final_price']-$info['data']['coupon_amount'])*$info['data']['commission_rate']/100;
            // 判断店铺券是否大于商品价格
            if ($info['data']['coupon_amount'] >= $info['data']['zk_final_price']){
                //佣金
                $info['data']['commission']=$info['data']['zk_final_price']*$info['data']['commission_rate']/100;
            }
            //保留2位小数，四舍五不入
            $info['data']['commission']=substr(sprintf("%.3f",$info['data']['commission']),0,-1);

            //商品详情页面地址
            //$info['data']['item_url']=$gy_data['item_url'];

            //获取商品详情内容
            $content_url='https://mdetail.tmall.com/templates/pages/desc?id='.$num_iid;
            $info['data']['content_url']=$content_url;
        }
        return $info;
    }

	/**
	 * 获取淘口令
	 * @param int $num_iid:淘宝商品ID
	 * @param string $pid:推广位
	 * @param string $relationId:渠道ID
     * @return array
	 */
	public function generateUrl($num_iid,$pid,$relationId ='')
	{
	    //调用高佣接口
	    //维易淘客-【辅助接口使用】id转高佣淘口令接口
	    $gy_appkey=$this->wykey;

        //生成渠道分享链接
        if($relationId){
            $url_gy_r = $this->url_wy . "/hcapi?vekey=$gy_appkey&para=$num_iid&pid=$pid&relationId=$relationId";
            $response = $this->request->get($url_gy_r);
            $result_gy_r = $response->array();

            $gy_data_r=$result_gy_r['data'];
            $res_info['tbk_pwd']=$gy_data_r['tbk_pwd']?:'淘口令';
            $res_info['ios_tbk_pwd']=$gy_data_r['ios_tbk_pwd']?:'ios淘口令';
            $res_info['global_tbk_pwd']=$gy_data_r['global_tbk_pwd']?:'淘口令';
            $title = $this->getNeedBetween($res_info['ios_tbk_pwd'], '【', '】');
            $res_info['new_tbk_pwd']= $res_info['ios_tbk_pwd'] . '😄' . $title;
            $res_info['new_tbk_pwd']= str_replace("￥", "", "8馥製選中这条₪".$gy_data_r['tbk_pwd']."₲,咑開【Ta0寳】抢购:".$title."/");
            $res_info['new_tbk_pwd']= $res_info['ios_tbk_pwd']. '/😄';
        }else {
            //此API不需要授权，适用于在已知产品有优惠券情况下（比如产品列表页传参）可以直接调用。不适用于对无优惠券商品的转链。
            $url_gy = $this->url_wy . "/hcapi?vekey=$gy_appkey&para=$num_iid&pid=$pid";
            $result_json_gy = $this->request->get($url_gy);

            $result_gy=$result_json_gy->array();
            $gy_data=$result_gy['data'];
            $res_info['tbk_pwd']=$gy_data['tbk_pwd']?:'淘口令';
            $res_info['ios_tbk_pwd']=$gy_data['ios_tbk_pwd']?:'ios淘口令';
            $res_info['global_tbk_pwd']=$gy_data['global_tbk_pwd']?:'淘口令';
            $title = $this->getNeedBetween($res_info['ios_tbk_pwd'], '【', '】');
            $res_info['new_tbk_pwd']= $res_info['ios_tbk_pwd'] . '😄' . $title;
            $res_info['new_tbk_pwd']= str_replace("￥", "", "8馥製選中这条₪".$gy_data['tbk_pwd']."₲,咑開【Ta0寳】抢购:".$title."/");
            $res_info['new_tbk_pwd']= $res_info['ios_tbk_pwd']. '/😄';
        }

	    return $res_info;
	}

    //截取指定2个字符之间字符串
    function getNeedBetween($input, $start, $end) {
        $substr = substr($input, strlen($start)+strpos($input, $start),(strlen($input) - strpos($input, $end))*(-1));
        return $substr;
    }    



    /**
     * 抓取订单
     * @param $params
     * @param int $query_type
     * @param string $tk_status
     * @param string $order_scene
     * @return mixed
     */
    public function fetchOrder($params, int $query_type = 2, int $tk_status = null, int $order_scene = 2)
    {
        if(!$this->startTime or !$this->endTime){
            return $this->error(1,'缺少时间字段');
        }
        if((strtotime($this->endTime)-strtotime($this->startTime))>3*3600){
            return $this->error(1,'时间间隔大于3小时');
        }
        $where = "&start_time={$this->startTime}&end_time={$this->endTime}";
        if ($query_type){
            $where .= "&query_type={$query_type}";
        }
        if (isset($params['position_index'])){
            $where .= "&position_index={$params['position_index']}";
        }
        if (isset($params['page_no'])){
            $where .= "&page_no={$params['page_no']}";
        }
        if (isset($params['page_size'])){
            $where .= "&page_size={$params['page_size']}";
        }
        if (isset($params['member_type'])){
            $where .= "&member_type={$params['member_type']}";
        }
        if ($tk_status){
            $where .= "&tk_status={$tk_status}";
        }
        if (isset($params['jump_type'])){
            $where .= "&jump_type={$params['jump_type']}";
        }
        if ($order_scene){
            $where .= "&order_scene={$order_scene}";
        }
        $url_order = $this->url_wy . "/orderdetails?vekey={$this->wykey}".$where;
        $response = $this->request->get($url_order);
        $result_order = $response->array();
        return $result_order;
    }    


}