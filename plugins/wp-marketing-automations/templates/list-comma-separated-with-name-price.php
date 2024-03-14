<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** checking if woocommerce exists other wise return */
if ( ! function_exists( 'bwfan_is_woocommerce_active' ) || ! bwfan_is_woocommerce_active() ) {
	return;
}

$exclude_variable_attribute = apply_filters( 'bwfan_exclude_wc_variable_attribute', false );

$product_names_with_price = [];

if ( false !== $cart ) {
	foreach ( $cart as $item ) {
		$product = isset( $item['data'] ) ? $item['data'] : '';
		if ( empty( $product ) || ! $product instanceof WC_Product ) {
			continue; // don't show items if there is no product
		}
		$price      = isset( $products_price[ $product->get_id() ] ) ? $products_price[ $product->get_id() ] : null;
		$line_total = is_null( $price ) ? BWFAN_Common::get_prices_with_tax( $product ) : $price;

		$name = $product->get_name();
		if ( $product instanceof WC_Product_Variation && false === $exclude_variable_attribute ) {
			$name .= ' - ' . $product->get_attribute_summary();
		}

		$product_names_with_price[] = esc_html__( $name ) . " ( " . $line_total . " )";
	}
} else {
	foreach ( $products as $product ) {
		$product_price = '';
		if ( ! $product instanceof WC_Product ) {
			continue;
		}
		$price      = isset( $products_price[ $product->get_id() ] ) ? $products_price[ $product->get_id() ] : null;
		$line_total = is_null( $price ) ? BWFAN_Common::get_prices_with_tax( $product ) : $price;
		$name       = $product->get_name();
		if ( $product instanceof WC_Product_Variation && false === $exclude_variable_attribute ) {
			$name .= ' - ' . $product->get_attribute_summary();
		}

		$product_names_with_price[] = esc_html__( $name ) . " ( " . $line_total . " )";
	}
}

$explode_operator = apply_filters( 'bwfan_product_name_separator', ', ' );
echo implode( $explode_operator, $product_names_with_price ); //phpcs:ignore WordPress.Security.EscapeOutput
