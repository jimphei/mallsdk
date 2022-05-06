<?php
/**
 * Created by PhpStorm.
 * User: jimphei
 * Date: 2021-06-08
 * Time: 10:52
 */

namespace Jimphei\mallsdk\taobao;


interface RequestInterface
{
    /**
     * 官方活动转链
     * @param int $act_id
     * @param int $relation_id
     * @return mixed
     */
    public function activityLink(int $act_id,int $relation_id = 0);

    /**
     * 生成口令
     * @param string $text
     * @param string $url
     * @return mixed
     */
    public function createKouling(string $text,string $url);

    /**
     * 订单开始实际
     * @param string $start_time
     * @return mixed
     */
    public function from(string $start_time);

    /**
     * 订单结束时间
     * @param string $end_time
     * @return mixed
     */
    public function to(string $end_time);

    /**
     * 抓取订单
     * @param $params
     * @param int $query_type
     * @param string $tk_status
     * @param string $order_scene
     * @return mixed
     */
    public function fetchOrder($params,int $query_type = 4,int $tk_status = null, int $order_scene=1);


}