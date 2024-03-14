<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$data = WShop_Temp_Helper::clear('atts','templates');
$order = $data['order'];

$order_items = $order->get_order_items();
if(!$order_items){
    return;
}
foreach ($order_items as $order_item):?>
    <a href="<?php echo get_permalink($order_item->post_ID)?>" class="xunhu-link-default"><?php echo $order_item->get_title().' X'.$order_item->qty;?></a><br/>
<?php endforeach;?>