<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** checking if woocommerce exists other wise return */
if ( ! function_exists( 'bwfan_is_woocommerce_active' ) || ! bwfan_is_woocommerce_active() ) {
	return;
}

$exclude_variable_attribute = apply_filters( 'bwfan_exclude_wc_variable_attribute', false );

$product_names_with_sku = [];
if ( false !== $cart ) {
	foreach ( $cart as $item ) {
		$product = isset( $item['data'] ) ? $item['data'] : '';
		if ( empty( $product ) || ! $product instanceof WC_Product ) {
			continue; // don't show items if there is no product
		}
		$product_sku = isset( $products_sku[ $product->get_id() ] ) && ! empty( $products_sku[ $product->get_id() ] ) ? " ( " . $products_sku[ $product->get_id() ] . " )" : '';

		$name = $product->get_name();
		if ( $product instanceof WC_Product_Variation && false === $exclude_variable_attribute ) {
			$name .= ' - ' . $product->get_attribute_summary();
		}

		$product_names_with_sku[] = esc_html__( $name . $product_sku );
	}
} else {
	foreach ( $products as $product ) {
		$product_sku = '';
		if ( ! $product instanceof WC_Product ) {
			continue;
		}
		$product_sku = isset( $products_sku[ $product->get_id() ] ) && ! empty( $products_sku[ $product->get_id() ] ) ? " ( " . $products_sku[ $product->get_id() ] . " )" : '';

		$name = $product->get_name();
		if ( $product instanceof WC_Product_Variation && false === $exclude_variable_attribute ) {
			$name .= ' - ' . $product->get_attribute_summary();
		}

		$product_names_with_sku[] = esc_html__( $name . $product_sku );
	}
}

$explode_operator = apply_filters( 'bwfan_product_name_separator', ', ' );
echo implode( $explode_operator, $product_names_with_sku ); //phpcs:ignore WordPress.Security.EscapeOutput
