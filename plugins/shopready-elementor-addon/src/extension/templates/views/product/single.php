<?php
/*
* QuomodoSoft
* Single Product Template File
*/
defined('ABSPATH') || exit;

// usage area notification, alert, form Error
do_action( 'mangocube_template_common','single');
// Woocommerce Default Hook
do_action( 'woocommerce_before_single_product' );

?>

<div class="shop-ready-single-product-container">

    <?php  do_action( 'shop_ready_single_product_notification' ); ?>

    <div id="product-<?php the_ID(); ?>" <?php post_class('shop-ready-wc-product'); ?>>
        <?php do_action( 'mangocube_act_tpl_single' ); ?>
    </div>

</div>

<?php
// Woocommerce Default Hook
do_action( 'woocommerce_after_single_product' );