<?php
if (!defined('ABSPATH')) {
    exit();
}
$data = WShop_Temp_Helper::clear('atts', 'templates');
$order = isset($data['order']) ? $data['order'] : null;
if (!$order || !$order instanceof WShop_Order) {
    WShop::instance()->WP->wp_die(WShop_Error::err_code(404), false, false);
    return;
}
$order_items = $order->get_order_items();
?>
<style type="text/css">
    body{background-color: #fff;}
</style>
<div class="xunhu-text-center xunhu-font font-24">支付成功！</div>
<div class="xunhu-downbox  xunhu-radius  xunhu-bg-color xunhu-pr xunhu-font xunhu-mr-auto xunhu-ml-auto  xunhu-mt10 " style="width: 700px!important;max-width: 100%;">
    <div class="xunhu-flex xunhu-justify-content-center xunhu-p20 xunhu-align-items-center">
        <div class="xunhu-order-status-ok"></div>
        <div class="xunhu-mr-auto xunhu-ml10">
            <div class="xunhu-font font-20 xh-text-left">支付成功，我们已收到订单</div>
            <div class="xh-text-left">订单内容：
                <?php foreach ($order_items as $order_item):?>
                    <a href="<?php echo $order_item->get_link();?>" class="xunhu-link-default"> <?php echo $order_item->get_title()?></a>&nbsp;
                <?php endforeach;?>
            </div>
        </div>
        <div><a href="<?php echo $order->get_back_url(); ?>" class="xunhu-btn xunhu-btn-green">返回</a></div>
    </div>
</div>
<script type="text/javascript">
    (function ($) {
        setTimeout(function(){
            location.href='<?php echo $order->get_back_url();?>';
        },2000);
    })(jQuery);
</script>
