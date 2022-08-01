<?php
/**
 * Created by PhpStorm.
 * User: jimphei
 * Date: 2021-06-08
 * Time: 12:52
 */

namespace Jimphei\mallsdk\pinduoduo;


interface RequestInterface
{
    /**
     * 订单开始时间
     * @param int $startTime
     * @return mixed
     */
    public function from(int $startTime);

    /**
     * 设置订单结束时间
     * @param int $endtime
     * @return mixed
     */
    public function to(int $endtime);

    /**
     * 抓取订单
     * @param array $params
     * @param bool $return_count
     * @return mixed
     */
    public function fetchOrder($params=[],$return_count = true);

    /**
     * 查询订单详情
     * @param string $order_sn
     * @return mixed
     */
    public function getOrderDetail(string $order_sn);

    /**
     * 生成连接
     * @param string $goods_id
     * @param array $custom_parameters
     * @return mixed
     */
    public function genLink($goods_id,array $custom_parameters,$params = [],$need_auth=false);

    /**
     * 频道推广连接
     * @param int $resource_type
     * @param array $custom_parameters
     * @return mixed
     */
    public function getResourceLink(int $resource_type,array $custom_parameters,string $url = '');

    /**
     * 查询商品标签列表
     * @return mixed
     */
    public function getGoodsOpts(int $parent_opt_id);

    /**
     *  商品标准类目接口 -- 用上面的分类就好
     * @params int $parent_cat_id:值=0时为顶点cat_id,通过树顶级节点获取cat树
     */
    public function getGoodsCats(int $parent_cat_id);

    /**
     * 生成商城-频道推广链接
     */
    public function getChannelLink($p_id_list,array $custom_parameters,int $channel_type=0);

    /**
     *  多多进宝商品推荐API
     * @params int $channel_type:进宝频道推广商品: 1-今日销量榜,5-实时热销榜,6-实时收益榜
     * @params int $page:默认值1，商品分页数
     * @params int $page_size:默认20，每页商品数量
     */
    public function getRecommendGoods(int $cat_id =null,int $channel_type = 1,array $activity_tags = [],string $uid = null, int $page=1,int $pageSize = 20);

    /**
     *  多多进宝商品查询
     * @params int $cat_id:商品标签类目ID
     * @params string $keyword:商品关键词
     * @params int $sort_type:排序方式:0-综合排序;1-按佣金比率升序;2-按佣金比例降序;3-按价格升序;4-按价格降序;5-按销量升序;6-按销量降序;7-优惠券金额排序升序;8-优惠券金额排序降序;9-券后价升序排序;10-券后价降序排序;13-按佣金金额升序排序;14-按佣金金额降序排序;
     * @params int $page:默认值1，商品分页数
     * @params int $page_size:默认20，每页商品数量
     * @params string $with_coupon:是否只返回优惠券的商品，false返回所有商品，true只返回有优惠券的商品
     */
    public function goodsSearch(int $opt_id = null,int $cat_id = null, string $keyword = '',int $sort_type=5,int $page = 1,int $pageSize = 20,$with_coupon = false,$pid=null);

    /**
     *  多多进宝商品详情查询
     * @params string $goods_id:商品ID
     */
    public function goodsDetail($goods_id,$uid = null,$pid = null);

    /**
     *  查询是否备案
     * 通过pid和自定义参数来查询是否已经绑定备案
     */
    public function queryIsAuthority(array $custom_parameters = [],string $pid = '');

    /**
     * 生成备案地址让用户点击进去备案，$channel_type:10
     */
    public function getAuthorityLink($uid,$p_id_list);

    /**
     * goodsID转sign，小技巧，通过pdd.ddk.goods.search传入goods_id来转
     * @param int $goods_id
     * @return mixed
     */
    public function goodsIdToSign(int $goods_id);
}
