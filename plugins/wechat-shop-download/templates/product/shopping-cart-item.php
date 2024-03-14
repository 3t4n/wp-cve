<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$data = WShop_Temp_Helper::clear('atts','templates');
$qty = intval($data['qty']);
$product = $data['product'];
$api = WShop_Settings_Checkout_Options::instance();
$modal = $api->get_option('modal');
$enable_inventory = $api->get_option('enable_inventory');
$inventory = $product->get('inventory');
$enable_ = $modal=='shopping_cart'&&$enable_inventory=='yes';
if(!$product instanceof Abstract_WShop_Product){
    return;
}
$context =$data['context'];
?>

<div class="xunhu-flex xunhu-justify-content-center xunhu-p20 xunhu-align-items-center">
    <div class=""><img src="<?php echo $product->get_img()?>"></div>
    <div class="xunhu-mr-auto xunhu-ml10">
        <div class="xunhu-font font-20 xh-text-left"><?php echo esc_html($product->get_title())?></div>
        <div class="xh-text-left"><?php echo $product->get_single_price(true)?>&nbsp;&nbsp;数量：x<?php echo $qty?></div>
    </div>
    <div>
        <a href="<?php echo $product->get_link()?>" class="xunhu-btn xunhu-btn-green">查看产品</a>
    </div>
</div>
<script type="text/javascript">
    (function($){
        $(document).bind('wshop_<?php echo $context;?>_init_amount_before',function(e,m){
            var price =<?php echo !$enable_||is_null($inventory)? $product->get_single_price(false):($product->get_single_price(false)*$qty);?>;
            m.total_amount+=price;
        });
    })(jQuery);
</script>
