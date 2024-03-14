<?php
if (!defined('ABSPATH')) {
    exit;
}

class WShop_Add_Ons_Sales_Statistics_Model extends Abstract_WShop_Order {

    //获取销售信息
    public function getSalesInfo() {
        $info = [
            'simple' => $this->getSimpleInfo(),
            'rank' => $this->getRankInfo()
        ];
        return $info;
    }

    //获取订单概况信息
    private function getSimpleInfo() {
        $data = [
            'total_sales' => 0,
            'today_sales' => 0,
            'today_orders' => 0,
            'total_orders' => 0
        ];
        global $wpdb;
        $todayStartTime=mktime(0,0,0,date('m'),date('d'),date('Y'));

        $total_sales_sql = "select o.currency,sum(o.total_amount) as total_sales from (
                            select oi.order_id
                            from {$wpdb->prefix}wshop_order_item oi
                            inner join {$wpdb->posts} p on p.ID = oi.post_ID
                            where p.post_status='publish'
                            group by oi.order_id
                        )g
                        inner join `{$wpdb->prefix}wshop_order` o on o.id = g.order_id
                        where o.status='processing' or o.status='complete';";
        $res1 = $wpdb->get_row($total_sales_sql);
        $data['total_sales']=WShop_Currency::get_currency_symbol($res1->currency).$res1->total_sales;

        $today_sales_sql = "select o.currency,sum(o.total_amount) as today_sales from (
                            select oi.order_id
                            from {$wpdb->prefix}wshop_order_item oi
                            inner join {$wpdb->posts} p on p.ID = oi.post_ID
                            where p.post_status='publish'
                            group by oi.order_id
                        )g
                        inner join `{$wpdb->prefix}wshop_order` o on o.id = g.order_id
                        where (o.status='processing' or o.status='complete') and o.order_date > ".$todayStartTime.";";
        $res2 = $wpdb->get_row($today_sales_sql);
        $data['today_sales']=WShop_Currency::get_currency_symbol($res2->currency).$res2->today_sales;


        $total_sales_sql = "select count(*) as 'today_orders' from (
                            select oi.order_id
                            from {$wpdb->prefix}wshop_order_item oi
                            inner join {$wpdb->posts} p on p.ID = oi.post_ID
                            where p.post_status='publish'
                            group by oi.order_id
                        )g
                        inner join `{$wpdb->prefix}wshop_order` o on o.id = g.order_id 
                        where (o.status='processing' or o.status='complete') and o.order_date > ".$todayStartTime.";";
        $res3 = $wpdb->get_row($total_sales_sql);
        $data['today_orders']=$res3->today_orders;

        $total_orders_sql = "select count(*) as 'total_orders' from (
                            select oi.order_id
                            from {$wpdb->prefix}wshop_order_item oi
                            inner join {$wpdb->posts} p on p.ID = oi.post_ID
                            where p.post_status='publish'
                            group by oi.order_id
                        )g
                        inner join `{$wpdb->prefix}wshop_order` o on o.id = g.order_id
                        where o.status='processing' or o.status='complete';";
        $res4 = $wpdb->get_row($total_orders_sql);
        $data['total_orders']=$res4->total_orders;
        return $data;
    }

    //获取销售排行
    private function getRankInfo() {
        global $wpdb;
        $sql="select p.post_title as name,g.price,g.sales_count from (
                select oi.post_ID,oi.price,count(oi.post_ID) as sales_count from {$wpdb->prefix}wshop_order_item oi 
                inner join {$wpdb->prefix}wshop_order o on o.id = oi.order_id where (o.status='processing' or o.status='complete') 
                group by oi.post_ID order by sales_count limit 0,20
                )g 
                inner join {$wpdb->prefix}posts p on p.ID = g.post_ID where p.post_status='publish';";
        $data = $wpdb->get_results( $sql);
        return $data;
    }
}