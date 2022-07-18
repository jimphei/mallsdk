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
                $gy_data_r=$result_gy_r['data'];
                $info['data']['coupon_click_url_r']=$gy_data_r['coupon_click_url'];
            }
            else{
                $info['data']['coupon_click_url_r']='';
            }


            //优惠券总量
            if($gy_data['coupon_total_count']) {
                $info['data']['coupon_total_count']=$gy_data['coupon_total_count'];
            }else {
                $info['data']['coupon_total_count']=0;
            }
            //优惠券剩余量
            if($gy_data['coupon_remain_count']) {
                $info['data']['coupon_remain_count']=$gy_data['coupon_remain_count'];
            }else {
                $info['data']['coupon_remain_count']=0;
            }
            //优惠券开始时间
            if($gy_data['coupon_start_time']) {
                $info['data']['coupon_start_time']=$gy_data['coupon_start_time'];
            }else {
                $info['data']['coupon_start_time']='';
            }
            //优惠券结束时间
            if($gy_data['coupon_end_time']) {
                $info['data']['coupon_end_time']=$gy_data['coupon_end_time'];
            }else {
                $info['data']['coupon_end_time']='';
            }
            //优惠券信息
            if($gy_data['coupon_info']) {
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
            if($gy_data['coupon_click_url']) {
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
            $info['data']['item_url']=$gy_data['item_url'];

            //获取商品详情内容
            $content_url='https://mdetail.tmall.com/templates/pages/desc?id='.$num_iid;
            $info['data']['content_url']=$content_url;
        }
        return $info;
    }



}