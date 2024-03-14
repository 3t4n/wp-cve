<?php
/**
 * Use this function to pull live API data. Only used
 * when called upon on AJAX requests.
 *
 * @since 1.1
 */

function cryptowp_api( $from = null, $to = null ) {
	$source = 'https://min-api.cryptocompare.com/data/all/coinlist';

	if ( isset( $from ) && isset( $to ) ) {
		$f = implode( ',', $from );
		$t = implode( ',', $to );
		$source = "https://min-api.cryptocompare.com/data/pricemultifull?fsyms={$f}&tsyms={$t}&extraParams=cryptowpcom";
	}

	$request = wp_remote_get( $source );

	if ( is_wp_error( $request ) )
		return;

	$body = wp_remote_retrieve_body( $request );

	return json_decode( $body );
}

/**
 * Get path of file from plugin or child theme.
 *
 * @since 1.2
 */

function cryptowp_template( $file ) {
	if ( $template = locate_template( "cryptowp/{$file}.php" ) )
		$path = $template;
	else
		$path = CRYPTOWP_DIR . "templates/{$file}.php";

	return $path;
}

/**
 * Helper function that gets display values in a clean way.
 *
 * @since 1.0
 */

function get_cryptowp( $field = null, $key = null, $atts = null ) {
	$val = '';
	$option = get_option( 'cryptowp' );

	if ( isset( $field ) && $field == 'ids' && ! empty( $option['coins'] ) ) {
		$val = array();
		foreach ( $option['coins'] as $symbol => $fields )
			$val[] = $symbol;
	}
	elseif ( isset( $field ) && isset( $key ) && isset( $atts ) )
		$val = ! empty( $option[$field][$key][$atts] ) ? $option[$field][$key][$atts] : '';
	elseif ( isset( $field ) && isset( $key ) )
		$val = ! empty( $option[$field][$key] ) ? $option[$field][$key] : '';
	elseif ( isset( $field ) )
		$val = ! empty( $option[$field] ) ? $option[$field] : '';
	else
		$val = $option;

	return $val;
}

/**
 * Get list of installed + filtered Currencies.
 *
 * @since 1.0
 */

function cryptowp_currencies() {
	$currencies = apply_filters( 'cryptowp_currencies', array(
		'AUD',
		'BRL',
		'CAD',
		'CHF',
		'CLP',
		'CNY',
		'CZK',
		'DKK',
		'EUR',
		'GBP',
		'HKD',
		'HUF',
		'IDR',
		'ILS',
		'INR',
		'JPY',
		'KRW',
		'MXN',
		'MYR',
		'NOK',
		'NZD',
		'PHP',
		'PKR',
		'PLN',
		'RUB',
		'SEK',
		'SGD',
		'THB',
		'TRY',
		'TWD',
		'ZAR'
	) );

	$filter = apply_filters( 'cryptowp_add_currencies', array() );

	return array_merge( $currencies, $filter );
}

/**
 * Prior to version 1.1, you could call a coin by entering the coin ID.
 * Due to API change, you now must use the coin ticker symbol. This function
 * ensures backwards compatibility and pulls proper coin data if ID is used
 * so existing users sites do not break. Will deprecate in a few versions.
 *
 * @since 1.1
 */

function get_cryptowp_coin_by_id( $coin ) {
	$show = '';
	$option = get_option( 'cryptowp' );

	if ( array_key_exists( $coin, $option['coins'] ) )
		$show = $coin;
	else
		foreach ( $option['coins'] as $symbol => $fields )
			if ( $coin == $fields['id'] )
				$show = $symbol;

	return $show;
}

/**
 * Get latest time down to the second of when file was last updated.
 *
 * @since 1.3
 */

function cryptowp_ver( $path ) {
	return date( 'ymds', filemtime( CRYPTOWP_DIR . $path ) );
}



/*-------------------------------------------------------------------------------------

/**
 * Call this function over the regular get_option() to get an
 * organized set of data about your coins.
 * Used internally by the widget and shortcode.
 *
 * @DEPRECATED 1.3
 * @since 1.0
 */
function cryptowp_data() {
	return get_cryptowp();
}
/**
 * A safe way to display coins, if a coin that's included
 * in output is ever removed from settings, this function
 * checks that and makes sure it doesn't show automatically.
 *
 * @since 1.0
 * @DEPRECATED 1.3
 */
function cryptowp_show( $coins ) {
	$show = array();
	$coins = array_keys( $coins );
	$crypto = cryptowp_data();
	if ( ! empty( $crypto['coins'] ) )
		foreach ( $crypto['coins'] as $id => $fields )
			if ( empty( $fields['error'] ) )
				$show[] = $id;
	return array_intersect( $coins, $show );
}