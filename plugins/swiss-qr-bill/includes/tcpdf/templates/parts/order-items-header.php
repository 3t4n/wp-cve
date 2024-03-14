<?php

$page_number_title = '';
if ( isset($page_num) && $page_num > 1 ) {
    $page_number_title = ', ' . __('Page', 'swiss-qr-bill') . ' ' . $page_num;
}

?>
<h5 id="order-header" style="font-size: 18px"><?php _e('Invoice', 'swiss-qr-bill'); ?>
    #<?php echo $order->get_order_number() . $page_number_title; ?></h5>