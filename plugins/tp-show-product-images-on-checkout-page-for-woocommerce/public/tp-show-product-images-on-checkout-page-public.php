<?php


//add_action( 'wp_enqueue_scripts', 'tpspicp_enqueue_styles_public' );
function tpspicp_enqueue_styles_public() {
    wp_enqueue_style( 'tp-show-product-images-on-checkout-page-public', plugin_dir_url( __FILE__ ) . 'css/tp-show-product-images-on-checkout-page-public.css', array(), TPSPICP_VERSION, 'all' );
}

//add_action( 'wp_enqueue_scripts', 'tpspicp_enqueue_scripts_public' );
function tpspicp_enqueue_scripts_public() {
    wp_enqueue_script( 'tp-show-product-images-on-checkout-page-public', plugin_dir_url( __FILE__ ) . 'js/tp-show-product-images-on-checkout-page-public.js', array( 'jquery' ), TPSPICP_VERSION, false );
}

add_filter( 'woocommerce_cart_item_name', 'tpspicp_product_image_review_order_checkout', 9999, 3 );
function tpspicp_product_image_review_order_checkout( $name, $cart_item, $cart_item_key ) {
    if ( ! is_checkout() ) return $name;

    $tpspicp_image_width  = get_option('tpspicp_image_width');
    //$tpspicp_image_height = get_option('tpspicp_image_height');
    $tpspicp_image_height = $tpspicp_image_width;
    $tpspicp_activate     = get_option('tpspicp_activate');
    $tpspicp_is_rtl       = get_option('tpspicp_is_rtl');
    $tpspicp_add_on_sale  = get_option('tpspicp_add_on_sale');

    $align_class = ($tpspicp_is_rtl) ? 'alignright' : 'alignleft';

    $product   = $cart_item['data'];
    // wp_dbug($product);
    //$is_on_sale = $product->is_on_sale();

    if($product->is_on_sale() && $tpspicp_add_on_sale){
        $tpspicp_label_sale      = get_option('tpspicp_label_sale');
        $tpspicp_on_sale = '<span class="tpspicp_on_sale">'.$tpspicp_label_sale.'</span>';
    }
    else{
        $tpspicp_on_sale = '';
    }

    $thumbnail = $product->get_image( array( $tpspicp_image_width, $tpspicp_image_height ), array( 'class' => $align_class . ' tpspicp_cart_image' ) );

    return $thumbnail . $name . ' '.$tpspicp_on_sale;
}

add_action( 'wp_footer', 'tpspicp_init_custom_css' );
function tpspicp_init_custom_css() {
    //$custom_css = get_option('tprvps_custom_css');
    $image_border_radius = get_option('tpspicp_image_border_radius');

    echo '<style>';
        echo '.tpspicp_cart_image{
            border-radius: '.$image_border_radius.'px;
        }';

        echo '.woocommerce-checkout .cart_item .product-name{
            position: relative;
        }';

        echo '.tpspicp_on_sale{
            position: absolute;
            left: 0;
            font-size: 10px;
            padding: 0px 5px;
            color: #fff;
            background: red;
        }';
    echo '</style>';
}

//--------------------------------------------------------------------------

/**
 * @snippet       Display Total Discount @ WooCommerce Cart/Checkout
 * @testedwith    WooCommerce 4.6
 */
 
add_action( 'woocommerce_cart_totals_after_order_total', 'tpspicp_show_total_discount_cart_checkout', 9999 );
add_action( 'woocommerce_review_order_after_order_total', 'tpspicp_show_total_discount_cart_checkout', 9999 );
function tpspicp_show_total_discount_cart_checkout() {

    $show_total_discount_cart = get_option('tpspicp_show_total_discount_cart');

    $discount_total = 0;
    
    foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {         
        $product = $values['data'];
        if ( $product->is_on_sale() ) {
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
            $discount = ( $regular_price - $sale_price ) * $values['quantity'];
            $discount_total += $discount;
        }
    }
             
    if ( $discount_total > 0  && $show_total_discount_cart) {
        $tpspicp_label_you_saved = get_option('tpspicp_label_you_saved');
        echo '<tr><th>'.esc_html($tpspicp_label_you_saved).'</th><td data-title="You Saved">' . wc_price( $discount_total + WC()->cart->get_discount_total() ) .'</td></tr>';
    }
  
}

//--------------------------------------------------------------------------

/**
 * @snippet       Show Regular/Sale Price @ WooCommerce Cart/Checkout Table
 * @testedwith    WooCommerce 3.8
 */
  
add_filter( 'woocommerce_cart_item_price', 'tpspicp_change_cart_table_price_display', 30, 3 );
add_filter( 'woocommerce_cart_item_subtotal', 'tpspicp_change_cart_table_price_display', 10, 3 );
function tpspicp_change_cart_table_price_display( $price, $values, $cart_item_key ) {
    $show_regular_sale_price_cart = get_option('tpspicp_show_regular_sale_price_cart');
    $slashed_price = $values['data']->get_price_html();
    $is_on_sale = $values['data']->is_on_sale();
    if ( $is_on_sale && $show_regular_sale_price_cart ) {
        $price = $slashed_price;
    }
    return $price;
}

//--------------------------------------------------------------------------