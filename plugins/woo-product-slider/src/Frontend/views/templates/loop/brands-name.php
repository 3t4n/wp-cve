<?php
/**
 * Product Brands Name template file.
 *
 * This template can be overridden by copying it to yourtheme/woo-product-slider-pro/templates/product-loop/brands-name.php
 *
 * @package    woo-product-slider-pro
 * @subpackage woo-product-slider-pro/Frontend
 */

$show_product_brands = isset( $shortcode_data['show_product_brands'] ) ? $shortcode_data['show_product_brands'] : false;

if ( $show_product_brands ) {
	if ( class_exists( 'ShapedPlugin\SmartBrands\SmartBrands' ) ) {
		do_action( 'sp_wps_brands_after_product' );
	}
}
