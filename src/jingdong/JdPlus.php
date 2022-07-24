<?php
/**
 * 项目 TKSAAS
 * Author jimphei
 * Date 2020-06-04
 */
namespace Jimphei\mallsdk\jingdong;


class JdPlus extends Jd
{
    protected $plus_app_id;
    protected $plus_app_secret;

    public function setPlusCofnig($app_id,$app_secret){
        $this->plus_app_id = $app_id;
        $this->plus_app_secret = $app_secret;
    }


    /**
     * 关键词查询选品
     * @param number $cat1Id:一级类目
     * @param number $cat2Id:二级类目
     * @param number $cat3Id:三级类目
     * @param string $keyword:关键词
     * @param number $page_index:页码
     * @param number $page_size:每页数量
     * @param string $sort_name:排序字段[pcPrice pc价],[pcCommission pc佣金],[pcCommissionShare pc佣金比例],[inOrderCount30Days 30天引入订单量],[inOrderComm30Days 30天支出佣金]
     * @param string $sort:	asc,desc升降序,默认降序
     * @return Ambigous <unknown, mixed>
     */
    public function searchGoods($cat1Id='',$cat2Id='',$cat3Id='',$keyword='',$page_index=1,$page_size=10,$sort_name='',$sort='desc')
    {
        $client = new \JdClient();
        $client->appKey = $this->plus_app_id;
        $client->appSecret = $this->plus_app_secret;  
        
        $req = new \UnionOpenGoodsQueryRequest();
        $goodsReqDTO= new \UnionOpenGoodsQuery\GoodsReqDTO;

        $goodsReqDTO->setCid1($cat1Id);
        $goodsReqDTO->setCid2($cat2Id);
        $goodsReqDTO->setCid3($cat3Id);
        $goodsReqDTO->setKeyword($keyword);
        $goodsReqDTO->setPageSize($page_size);
        $goodsReqDTO->setPageIndex($page_index);
        $goodsReqDTO->setSortName($sort_name);
        $goodsReqDTO->setSort($sort);

        $req->setGoodsReqDTO($goodsReqDTO);
        
        $req->setVersion("1.0");

        return $client->execute($req);
    }    

}