<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$data = WShop_Temp_Helper::clear('atts','templates');
$location = isset($data['location'])?$data['location']:null;
$pageSize = isset($data['pageSize'])?intval($data['pageSize']):20;
if(!is_user_logged_in()){
   ?>
   <script type="text/javascript">
		location.href='<?php echo wp_login_url(WShop_Helper_Uri::get_location_uri())?>';
	</script>
   <?php
    return;
}

$pageIndex = isset($_REQUEST['pageIndex'])?absint($_REQUEST['pageIndex']):0;
if($pageIndex<1){
    $pageIndex=1;
}

$user_id = get_current_user_id();

global $wpdb;
$query =$wpdb->get_row(
   "select count(o.id) as qty
    from {$wpdb->prefix}wshop_order o
    where o.removed=0
          and o.status!='".WShop_Order::Unconfirmed."'
          and o.customer_id ={$user_id};");
$total_qty = intval($query->qty);
if($total_qty>0&&$pageIndex>$total_qty){
    $pageIndex = $total_qty;
}

$pageCount = absint(ceil($total_qty/($pageSize*1.0)));
$start = ($pageIndex-1)*$pageSize;

$orders = $wpdb->get_results(
    "select o.*
    from {$wpdb->prefix}wshop_order o
    where o.removed=0
          and o.status!='".WShop_Order::Unconfirmed."'
          and o.customer_id ={$user_id}
    order by o.id desc
    limit $start,$pageSize;");
?>
<div class="xunhu-p20">

    <div class="xunhu-font font-20"><?php echo __('My Orders',WSHOP)?></div>
    <div class="xunhu-divider"></div>
    <table class="xunhu-table xunhu-font">
        <thead>
        <tr>
            <th><?php echo __('ID',WSHOP)?></th>
            <th>订单内容</th>
            <th><?php echo __('Date',WSHOP)?></th>
            <th><?php echo __('Total',WSHOP)?></th>
            <th>支付方式</th>
            <th><?php echo __('Status',WSHOP)?></th>
            <th><?php echo __('Toolbar',WSHOP)?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(!$orders||count($orders)==0){?>
            <tr><td colspan="7"><?php echo __( "You don't have any orders!", WSHOP ) ;?></td></tr>
        <?php
        }else{
            $context = WShop_Helper::generate_unique_id();
            foreach ($orders as $wp_order){
                $order = new WShop_Order($wp_order);
                ?>
                <tr id="order_item_<?php echo $order->id;?>">
                    <th class="order-item-id" scope="row">#<?php echo $order->id?></th>
                    <td class="order-item-products"><?php echo $order->order_items_view_my_orders_list_item()?></td>
                    <td class="order-item-date"><?php echo date('Y-m-d H:i',$order->order_date)?></td>
                    <td class="order-item-amount"><?php echo $order->get_total_amount(true)?></td>
                    <td class="order-item-payment">
                        <?php
                        $payment=$order->get_payment_gateway();
                        if($payment){
                            echo $payment->title;
                        }else{
                            echo '其他';
                        }
                        ?>
                    </td>
                    <td class="order-item-status"><?php echo $order->get_order_status_html()?></td>
                    <td>
                        <span class="order-item-transaction_id" style="display: none;"><?php echo $order->transaction_id?></span>
                        <a href="javascript:void(0);" class="xunhu-link-default" onclick="xh_wshop_view_order_<?php echo $context;?>(<?php echo $order->id;?>);"><?php echo __('View',WSHOP)?></a>
                        <?php if($order->can_pay()): ?>
                            <a href="<?php echo $order->get_pay_url()?>" class="xunhu-link-default"><?php echo __('Pay',WSHOP)?></a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php }
        }
        ?>
        </tbody>
    </table>
    <?php
    //分页
    require_once WSHOP_DIR.'/includes/paging/class-xh-paging-model.php';
    if(empty($location)){
        $location = WShop_Helper_Uri::get_location_uri();
    }
    $pagging = new WShop_Paging_Model($pageIndex, $pageSize, $total_qty,function($pageIndex,$location){
        return WShop_Helper_Uri::get_new_uri($location,array('pageIndex'=>$pageIndex));
    },$location);
    echo $pagging->newPaging();
    ?>
</div>
<!--订单详情弹窗内容-->
<script type="text/javascript">
    (function ($) {
        window.xh_wshop_view_order_<?php echo $context;?>=function (orderid) {
            var node=$('#xh_wshop_order_detail_modal');
            if(node.is(':hidden')){
                //生成弹窗html
                var html=create_xh_wshop_view_order_modal_html(orderid);
                $('.show-order-detail-container').html(html);
                node.show();
            }else{
                node.hide();
            }
        }

        function create_xh_wshop_view_order_modal_html(orderid) {
            var orderObj=$('#order_item_'+orderid);
            var html="<li>所购商品："+orderObj.children('.order-item-products').html()+"</li>\n" +
                        "<li>付款金额："+orderObj.children('.order-item-amount').html()+"</li>\n" +
                        "<li>订单编号："+orderObj.children('.order-item-id').html()+"</li>\n" +
                        "<li>交易时间："+orderObj.children('.order-item-date').html()+"</li>";
            if(orderObj.find('.order-item-transaction_id').html().length>0){
                html+="<li>支付方式："+orderObj.children('.order-item-payment').html()+"</li>\n" +
                        "<li>交易编号："+orderObj.find('.order-item-transaction_id').html()+"</li>";
            }
            html+="<li>订单状态："+orderObj.children('.order-item-status').html()+"</li>";
            return html;
        }
    })(jQuery);
</script>
<div id="xh_wshop_order_detail_modal" class="xunhu-modal" style="display: none;">
    <div class="xunhu-modal-content">
        <span class="xunhu-close" onclick="XH_Plugins_Custom.close_model();"></span>
        <div class="xunhu-p20">
            <div class="xunhu-font font-20">订单详情</div>
            <div class="xunhu-divider"></div>
            <ul class="xunhu-order-info xunhu-font show-order-detail-container">
                <li>所购商品：<a href="" class="xunhu-link-default">付费查看内容3：文章有多个付费内容</a></li>
                <li>付款金额：￥199元</li>
                <li>订单编号：#14088</li>
                <li>交易时间：2019-01-05 11:41</li>
                <li>支付方式：微信支付</li>
                <li>交易编号：201124000000000000001454</li>
                <li>订单状态：处理中</li>
            </ul>
        </div>
    </div>
</div>
