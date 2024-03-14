<?php
if (! defined('ABSPATH')) {
    exit();
}
$context = WShop_Helper::generate_unique_id();
?>
<div class="xunhu-cart-layout">
    <div class="xunhu-cart-box xunhu-font">
        <div class="xunhu-steps">
            <span>选择商品</span>
            <span class="active">确认付款</span>
            <span>下单成功</span>
        </div>
        <?php
        //购物车
        echo WShop::instance()->WP->requires(WSHOP_DIR, 'page/checkout-order-pay-shopping-cart.php',$context);

        //购物车扩展  -> 表单 /优惠券等
        $calls = apply_filters('wshop_checkout_cart',array(),$context);
        foreach ($calls as $call){
            $call($context);
        }

        //支付网关
        echo WShop::instance()->WP->requires(WSHOP_DIR, 'page/checkout-order-pay-payment-gateways.php',$context);

        //其它字段，比如条款等
        $calls = apply_filters('wshop_checkout_payment_gateways',array(),$context);
        foreach ($calls as $call){
            $call($context);
        }
        ?>
        <!-- 结算表单 -->
        <form class="xunhu-font xunhu-mt20">
            <div class="xunhu-font xunhu-text-center"><span id="wshop-<?php echo $context?>-actual-amount" class="font-24 text-warning"></span></div>
            <?php
            echo WShop::instance()->WP->requires(WSHOP_DIR, 'page/checkout-order-pay-total-amount.php',array(
                'context'=>$context
            ));
            ?>
            <script type="text/javascript">
                (function($){
                    $(document).bind('wshop_<?php echo $context?>_show_amount',function(e,view){
                        var total =view.total_amount;
                        if(total<=0){
                            $('#wshop-<?php echo $context?>-actual-amount').html('').hide();
                        }else{
                            $('#wshop-<?php echo $context?>-actual-amount').html('<?php echo __('Total:',WSHOP)?>'+view.symbol+total.toFixed(2)).show();
                        }
                    });
                })(jQuery);
            </script>
            <?php
            echo WShop::instance()->WP->requires(WSHOP_DIR, '__purchase.php',array(
                'content'=>__('Pay Now',WSHOP),
                'class'=>'',
                'context'=>$context,
                'tab'=>'purchase_modal_shopping_cart_create_order',
                'modal'=>'shopping'
            ));
            ?>
        </form>
    </div>
</div>
