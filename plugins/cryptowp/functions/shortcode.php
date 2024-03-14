<?php
/**
 * Use the [crypto] shortcode to output coin data anywhere
 * in WordPress that accepts shortcodes. This function sorts
 * data to be passed to the shortcode template.
 *
 * @since 1.0
 */

function cryptowp_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'coins' => '',
		'show' => '',
		'type' => '',
		'hide_icon' => '',
		'hide_percent' => '',
		'classes' => '',
		'columns' => '',
		'calc' => ''
	), $atts, 'crypto' ) );

	static $i = 0;
	$c = 1;
	$i++;

	$coins = ! empty( $atts['coins'] ) ? explode( ',', str_replace( ' ', '', $atts['coins'] ) ) : get_cryptowp( 'coins' );
	$id = "cryptowp_shortcode_{$i}";
	$calc = isset( $atts['calc'] ) ? $atts['calc'] : '';
	$columns = isset( $atts['columns'] ) ? $atts['columns'] : '';
	$layout = isset( $atts['type'] ) ? $atts['type'] : '';
	$hide_icon = isset( $atts['hide_icon'] ) ? true : false;
	$hide_percent  = isset( $atts['hide_percent'] ) ? true : false;
	$classes = isset( $atts['classes'] ) ? ' ' . $atts['classes'] : '';
	$currency_sign = get_cryptowp( 'currency_sign' ) ? get_cryptowp( 'currency_sign' ) : '$';

	$columns_style = ! empty( $columns ) && $columns >= 2 ? ' style="width: ' . ( 100 / intval( $columns ) ) . '%"' : '';
	$columns_classes = ! empty( $columns ) && $columns >= 2 ? ' cryptowp-columns' : '';
	$layout_classes = 'cryptowp-' . ( ! empty( $layout ) ? $layout : 'grid' );
	$coins_classes = $layout_classes . $columns_classes;
	ob_start();

	if ( $layout == 'text' ) {
		$show = ! empty( $atts['show'] ) ? $atts['show'] : 'price';
		$coin = get_cryptowp_coin_by_id( $coins[0] );
		$price_val  = get_cryptowp( 'coins', $coin, 'price' );
		$value = get_cryptowp( 'coins', $coin, 'value' );
		$value_hour = get_cryptowp( 'coins', $coin, 'value_hour' );
		$percent = get_cryptowp( 'coins', $coin, 'percent' );
		$percent_hour = get_cryptowp( 'coins', $coin, 'percent_hour' );
		$mktcp = get_cryptowp( 'coins', $coin, 'market_cap' );
		$market_cap = $mktcp ? number_format( $mktcp, 2 ) : '';
		$price_btc = get_cryptowp( 'coins', $coin, 'price_btc' );
		$spply = get_cryptowp( 'coins', $coin, 'supply' );
		$supply = $spply ? number_format( $spply ) : '';
		if ( ! empty( $calc ) )
			$price = number_format( ( $calc / str_replace( ',', '', $price_val ) ), 4 );
		else
			$price = $price_val;
		include( cryptowp_template( 'cryptowp-text' ) );
	}
	else
		include( cryptowp_template( 'cryptowp' ) );

	return ob_get_clean();
}

add_shortcode( 'crypto', 'cryptowp_shortcode' );