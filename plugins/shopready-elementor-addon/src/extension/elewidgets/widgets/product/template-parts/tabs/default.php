<?php 
defined( 'ABSPATH' ) || exit;
global $product;

if( shop_ready_is_elementor_mode() ){
  
    $temp_id = $settings['wready_product_id'];
   if( is_numeric( $temp_id ) ){
        $GLOBALS['post'] = get_post( $temp_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
        setup_postdata( $GLOBALS['post'] );
    }else{
        $GLOBALS['post'] = get_post( shop_ready_get_single_product_key() ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
        setup_postdata( $GLOBALS['post'] );
    }

    $hide_tabs = [];
 
}

if ( empty( $product ) ) {
    return;
}

wc_get_template( 'single-product/tabs/tabs.php' );

// On render widget from Editor - trigger the init manually.
if ( wp_doing_ajax() ) {
    ?>
    <script>
        jQuery( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' );
    </script>
    <?php
}