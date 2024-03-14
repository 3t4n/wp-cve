<?php 
if (! defined('ABSPATH')) {
    exit();
}

$context = WShop_Temp_Helper::clear('atts','templates');

$shopping_cart = WShop_Shopping_Cart::get_cart();

if($shopping_cart instanceof WShop_Error){
    WShop::instance()->WP->wp_die($shopping_cart,false,false);
    return;
}

$shopping_cart_items = $shopping_cart->get_items();
?>
<div class="xunhu-downbox  xunhu-radius  xunhu-bg-color xunhu-pr xunhu-font xunhu-mr-auto xunhu-ml-auto  xunhu-mt10 ">
    <?php
    if($shopping_cart_items instanceof WShop_Error){
        echo '<div class="xunhu-alert xunhu-alert-danger">'.$shopping_cart_items->errmsg.'</div>';
    }else if(count($shopping_cart_items)>0){
        foreach ($shopping_cart_items as $post_id=>$item){
            $product = $item['product'];
            echo $product->shopping_cart_item_html($shopping_cart,$item['qty'],$context);
        }
    }else{
        echo '<div>购物车中暂无商品！</div>';
    }
    ?>
</div>

<?php do_action('wshop_checkout_order_pay_shopping_cart',$context); ?>