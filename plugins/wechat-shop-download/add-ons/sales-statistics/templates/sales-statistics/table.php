<?php
$SalesStatisticsModel=new WShop_Add_Ons_Sales_Statistics_Model();
$data=$SalesStatisticsModel->getSalesInfo();    
?>
<style type="text/css">
    .xh_wshop_sales_statistics_widget{
        width: 100%;
        font-size: 14px;
        color: #444;
    }
    .xh_wshop_sales_statistics_widget tr{
        flex: 1;
    }
    .xh_wshop_sales_statistics_widget td{
        padding: 5px 0;
    }
    .xh_wshop_sales_statistics_widget .ttl{
        font-size: 16px;
        color: #21759b;
    }
    .xh_wshop_sales_statistics_widget .ttl2{
        border-top: 1px solid #ececec;
    }
    .xh_wshop_sales_statistics_widget .item-ttl{
        width: 25%;
        text-align: center;
    }
    .xh_wshop_sales_statistics_widget .item-val{
        width: 25%;
        text-align: center;
    }
</style>
<table class="xh_wshop_sales_statistics_widget">
    <tr><td colspan="4" class="ttl">概况</td></tr>
    <tr>
        <td class="item-ttl">总销量</td>
        <td class="item-ttl">今日销量</td>
        <td class="item-ttl">今日订单</td>
        <td class="item-ttl">总订单</td>
    </tr>
    <tr>
        <td class="item-val"><a href="#"><?php echo $data['simple']['total_sales']?$data['simple']['total_sales']:0;?></a></td>
        <td class="item-val"><a href="#"><?php echo $data['simple']['today_sales']?$data['simple']['today_sales']:0;?></a></td>
        <td class="item-val"><a href="#"><?php echo $data['simple']['today_orders']?$data['simple']['today_orders']:0;?></a></td>
        <td class="item-val"><a href="#"><?php echo $data['simple']['total_orders']?$data['simple']['total_orders']:0;?></a></td>
    </tr>
    <tr><td colspan="4" class="ttl ttl2">销售排行</td></tr>
    <?php if($data['rank']){
        foreach ($data['rank'] as $k=>$v){?>
            <tr>
                <td colspan="4"><?php echo ($k+1).'&nbsp;&nbsp;<a href="#">'.$v->name.'</a>&nbsp;&nbsp;价格:'.$v->price.'&nbsp;&nbsp;购买次数:'.$v->sales_count;?></td>
            </tr>
        <?php }
    }else{?>
        <tr><td colspan="4">暂无销售数据</td></tr>
    <?php }?>
</table>