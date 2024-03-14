<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** checking if woocommerce exists other wise return */
if ( ! function_exists( 'bwfan_is_woocommerce_active' ) || ! bwfan_is_woocommerce_active() ) {
	return;
}

$exclude_variable_attribute = apply_filters( 'bwfan_exclude_wc_variable_attribute', false );

$product_names = [];
if ( false !== $cart ) {
	foreach ( $cart as $item ) {
		$product = isset( $item['data'] ) ? $item['data'] : '';
		if ( empty( $product ) || ! $product instanceof WC_Product ) {
			continue; // don't show items if there is no product
		}
		$name = $product->get_name();
		if ( $product instanceof WC_Product_Variation && false === $exclude_variable_attribute ) {
			$name .= ' - ' . $product->get_attribute_summary();
		}
		$product_names[] = esc_html__( $name );
	}
} else {
	foreach ( $products as $product ) {
		if ( ! $product instanceof WC_Product ) {
			continue;
		}
		$name = $product->get_name();
		if ( $product instanceof WC_Product_Variation && false === $exclude_variable_attribute ) {
			$name .= ' - ' . $product->get_attribute_summary();
		}
		$product_names[] = esc_html__( $name );
	}
}

$explode_operator = apply_filters( 'bwfan_product_name_separator', ', ' );
echo implode( $explode_operator, $product_names ); //phpcs:ignore WordPress.Security.EscapeOutput
