<?php 
namespace Adminz\Helper;
class ADMINZ_Helper_Product_Variation {
	
	function __construct() {
		add_filter( 'woocommerce_variable_sale_price_html', [$this,'wc_wc20_variation_price_format'], 10, 2 );
		add_filter( 'woocommerce_variable_price_html', [$this,'wc_wc20_variation_price_format'], 10, 2 );
		//Grouped products
		// Show product prices in WooCommerce 2.0 format
		add_filter( 'woocommerce_grouped_price_html', [$this,'wc_wc20_grouped_price_format'], 10, 2 );
		add_action('wp_footer',[$this,'fix_footer']);
		// hide small price 
		add_action('woocommerce_after_single_variation',[$this,'fix_after_single_variation']);
	}
	function wc_wc20_variation_price_format( $price, $product ) {
	    // Main prices
	    $prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
	    $price = $prices[0] !== $prices[1] ? sprintf( __( '%1$s'), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
	    // Sale price
	    $prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
	    sort( $prices );
	    $saleprice = $prices[0] !== $prices[1] ? sprintf( __( '%1$s'), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
	    if ( $price !== $saleprice ) {
	        $price = '<del>' . $saleprice . '</del> <ins>' . $price . '</ins>';
	    }
	    return $price . $product->get_price_suffix();
	}
	
	function wc_wc20_grouped_price_format( $price, $product ) {
	    $tax_display_mode = get_option( 'woocommerce_tax_display_shop' );
	    $child_prices     = array();
	    foreach ( $product->get_children() as $child_id ) {
	        $child_prices[] = get_post_meta( $child_id, '_price', true );
	    }
	    $child_prices     = array_unique( $child_prices );
	    $get_price_method = 'get_price_' . $tax_display_mode . 'uding_tax';
	    if ( ! empty( $child_prices ) ) {
	        $min_price = min( $child_prices );
	        $max_price = max( $child_prices );
	    } else {
	        $min_price = '';
	        $max_price = '';
	    }
	    if ( $min_price == $max_price ) {
	        $display_price = wc_price( $product->$get_price_method( 1, $min_price ) );
	    } else {
	        $from          = wc_price( $product->$get_price_method( 1, $min_price ) );
	        $display_price = sprintf( __( 'From %1$s'), $from );
	    }
	    return $display_price;
	}

	/// script when choose variation price
	function fix_footer(){
		ob_start();
	    ?>
	    <script type="text/javascript">
	        jQuery(document).ready(function($){
	            $(".single_variation_wrap").change(function(){
	                var new_html_price = $(this).find(".price").html();
	                $(this).closest(".product-main").find(".price-wrapper .amount").html(new_html_price);
	            });
	        });
	    </script>
	    <?php
	    echo ob_get_clean();
	}	

	function fix_after_single_variation(){
		ob_start();
	    ?>
	    <style type="text/css">
	        .single_variation_wrap .woocommerce-variation-price{
	            display: none;
	        }
	    </style>
	    <?php
	    echo ob_get_clean();
	}
	
}