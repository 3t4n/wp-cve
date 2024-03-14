<?php
/**
 * This class contains various processes used to interact
 * with data in the background of the CryptoWP interface.
 *
 * @since 1.0
 */

class CryptoWP_Processes {

	/**
	 * Launch processes on WordPress init.
	 */

	public function __construct() {
		add_action( 'init', array( $this, 'autorefresh' ) );
		add_action( 'wp_ajax_process', array( $this, 'process' ) );
		add_action( 'wp_ajax_nopriv_process', array( $this, 'process' ) );
	}

	/**
	 * Call the autorefresh process when the transient is
	 * deleted and coins are in database.
	 *
	 * @since 1.0
	 */

	public function autorefresh() {
		$name = 'cryptowp_autorefresh';
		$transient = get_transient( $name );

		if ( ! empty( $transient ) )
			return;

		$option = get_option( 'cryptowp' );

		if ( empty( $option['coins'] ) )
			return;

		$coins = wp_list_pluck( $option['coins'], 'symbol' );
		$coins = array_filter( $coins );
		$this->run( 'refresh', $coins );

		set_transient( $name, true, CRYPTOWP_AUTOREFRESH );
	}

	/**
	 * Run specified process (import or refresh) to get latest
	 * data from API. For best performance and to avoif API limits,
	 * split requests if greater than 50 into multiple requests.
	 *
	 * @since 1.0
	 */

	public function process() {
		parse_str( stripslashes( $_POST['form'] ), $form );

		$strings = cryptowp_strings();

		if ( ! wp_verify_nonce( $form['_wpnonce'], $form['option_page'] . '-options' ) )
			die ( $strings['connection_error'] );

		$args = array();
		$option = get_option( 'cryptowp' );
		$process = esc_html( $_POST['process'] );

		// Determine which process to run (slight differences in handling)

		if ( $process == 'import' ) {
			$input = str_replace( ' ', '', strtoupper( $_POST['coins_import'] ) );
			$input = ! empty( $input ) ? explode( ',', $input ) : '';
		}
		elseif ( $process == 'refresh' ) {
			$input = array();
			foreach ( $option['coins'] as $symbol => $fields )
				if ( ! empty( $symbol ) )
					$input[] = $symbol;
		}

		// Run options updater

		$this->run( $process, $input, $args );

		// Exit AJAX

		$admin = new CryptoWP_Admin;

		$admin->coins();

		die();
	}

	/**
	 * Call this function to run updater loop that updates coin settings.
	 *
	 * @since 1.0
	 */

	public function run( $process, $input, $args = null ) {
		$coins  = array();
		$all    = cryptowp_api();
		$option = get_option( 'cryptowp' );
		$rate   = 50;

		// Get currency info from settings

 		$curr     = ! empty( $option['currency'] ) ? esc_html( $option['currency'] ) : 'USD';
 		$currency = array( $curr, 'BTC' );
		$sign     = ! empty( $option['currency_sign'] ) ? esc_html( $option['currency_sign'] ) : '';

		// To meet API limits, group multiples of 50 coins into different requests

		$total = count( $input );

		if ( $total > $rate )
			$coins = array_chunk( $input, $rate, true );
		else
			$coins = array( $input );

		// Loop requests and pass coins through API, assign values to options database

		foreach ( $coins as $request => $symbols ) {

			$api = cryptowp_api( $symbols, $currency );

			if ( ! empty( $api->Response ) && $api->Response == 'Error' )
				continue;

			foreach ( $symbols as $order => $symbol ) {
				if ( ! isset( $api->RAW->{$symbol} ) )
					continue;

				$percent        = isset( $api->RAW->{$symbol}->{$curr}->CHANGEPCT24HOUR ) ? $api->RAW->{$symbol}->{$curr}->CHANGEPCT24HOUR : '';
				$percent_hour   = isset( $api->RAW->{$symbol}->{$curr}->CHANGEPCTHOUR ) ? $api->RAW->{$symbol}->{$curr}->CHANGEPCTHOUR : '';
				$coin_icon_path = isset( $all->Data->{$symbol}->ImageUrl ) ? $all->Data->{$symbol}->ImageUrl : '';
				$coin_name      = isset( $all->Data->{$symbol}->CoinName ) ? $all->Data->{$symbol}->CoinName : '';
				$price          = isset( $api->RAW->{$symbol}->{$curr}->PRICE ) ? $api->RAW->{$symbol}->{$curr}->PRICE : '';
				$btc_price      = isset( $api->DISPLAY->{$symbol}->BTC->PRICE ) ? $api->DISPLAY->{$symbol}->BTC->PRICE : '';
				$market_cap     = isset( $api->RAW->{$symbol}->{$curr}->MKTCAP ) ? $api->RAW->{$symbol}->{$curr}->MKTCAP : '';
				$supply         = isset( $api->RAW->{$symbol}->{$curr}->SUPPLY ) ? $api->RAW->{$symbol}->{$curr}->SUPPLY : '';

				if ( $process == 'refresh' ) {
					if ( empty( $api ) ) {
						unset( $option['coins'][$symbol]['price'] );
						unset( $option['coins'][$symbol]['percent'] );
						unset( $option['coins'][$symbol]['value'] );
						unset( $option['coins'][$symbol]['price_btc'] );
						unset( $option['coins'][$symbol]['market_cap'] );
						unset( $option['coins'][$symbol]['supply'] );
						$option['coins'][$symbol]['error'] = true;
					}
				}
				$dec  = $price < .01 ? 4 : 2;
				$data = array(
					'symbol'       => esc_html( $symbol ),
					'sign'         => esc_html( $sign ),
					'price'        => number_format( esc_html( $price ), $dec ),
					'price_btc'    => substr( esc_html( $btc_price ), 3 ),
					'market_cap'   => esc_html( $market_cap ),
					'supply'       => esc_html( $supply ),
					'percent'      => number_format( esc_html( $percent ), 2 ),
					'percent_hour' => number_format( esc_html( $percent_hour ), 2 ),
					'value'        => substr( esc_html( $percent ), 0, 1 ) == '-' ? 'decrease' : 'increase',
					'value_hour'   => substr( esc_html( $percent_hour ), 0, 1 ) == '-' ? 'decrease' : 'increase',
					'error'        => false
				);

				if ( $process == 'import' ) {
					$coin_icon = $this->upload_image( $coin_icon_path, $coin_name );
					$option['coins'][$symbol] = array_merge( array(
						'id'   => strtolower( str_replace( ' ' , '-', $coin_name ) ),
						'name' => esc_html( $coin_name ),
						'icon' => esc_url( $coin_icon ),
						'url'  => ''
					), $data );
				}
				elseif ( $process == 'refresh' )
					$option['coins'][$symbol] = array_merge( $option['coins'][$symbol], $data );

			}

		}

		// Save Options
		update_option( 'cryptowp', $option );
	}

	/**
	 * Using the WordPress API, pull the icon image from API and
	 * upload it to the site's Media Library for use around site.
	 * Upon completion, return attachment URL.
	 *
	 * @since 1.0
	 */

	public function upload_image( $coin_icon_path, $coin_name ) {
		// Upload image from CryptoCompare to Media Library with WP API
		if ( ! class_exists( 'WP_Http' ) )
			include_once( ABSPATH . WPINC . '/class-http.php' );

		$file_name = basename( $coin_icon_path );
		$url       = 'https://www.cryptocompare.com' . esc_attr( $coin_icon_path );
		$http      = new WP_Http();
		$response  = $http->request( $url );

		if ( $response['response']['code'] != 200 )
			return false;

		$upload = wp_upload_bits( esc_attr( $file_name ), null, $response['body'] );

		if ( ! empty( $upload['error'] ) )
			return false;

		$file_path     = $upload['file'];
		$wp_upload_dir = wp_upload_dir();
		$attachment_id = wp_insert_attachment( array(
			'guid'           => $wp_upload_dir['url'] . '/' . esc_attr( $file_name ),
			'post_mime_type' => 'image/png',
			'post_title'     => esc_html( $coin_name ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		), $file_path );

		// Include image.php

		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Define attachment metadata

		$attachment = wp_generate_attachment_metadata( $attachment_id, $file_path );

		// Assign metadata to attachment

		wp_update_attachment_metadata( $attachment_id,  $attachment );

		// Get URL from attachment ID

		$attachment_url = wp_get_attachment_url( $attachment_id );

		// Return URL to be saved to database

		return $attachment_url;
	}

}

new CryptoWP_Processes;