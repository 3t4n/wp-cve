<?php

namespace AppBuilder;

/**
 * Class Utils
 * @author ngocdt@rnlab.io
 * @since 1.0.0
 */
class Utils {
	/**
	 *
	 * Get content via URL
	 *
	 * @param $url
	 *
	 * @return bool|string
	 */
	public static function get_url_content( $url ) {
		$parts  = parse_url( $url );
		$host   = $parts['host'];
		$ch     = curl_init();
		$header = array(
			'GET /1575051 HTTP/1.1',
			"Host: {$host}",
			'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
			'Accept-Language:en-US,en;q=0.8',
			'Cache-Control:max-age=0',
			'Connection:keep-alive',
			'Host:adfoc.us',
			'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36',
		);

		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 0 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
		$result = curl_exec( $ch );
		curl_close( $ch );

		return $result;
	}

	/**
	 * Returns true if we are making a REST API request for App builder.
	 *
	 * @return  bool
	 */
	public static function is_rest_api_request() {
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		$rest_prefix = trailingslashit( rest_get_url_prefix() );
		$uri         = $_SERVER['REQUEST_URI'];
		$allows      = array( 'wc/store/cart', 'wc/store/checkout', 'app-builder/v1/points-and-rewards' );

		foreach ( $allows as $allow ) {
			$check = strpos( $uri, $rest_prefix . $allow ) !== false;
			if ( $check ) {
				return true;
			}
		}

		return false;
	}

	/**
	 *
	 * Handle download file
	 *
	 * @param $download_url
	 * @param $version
	 *
	 * @return bool|true|\WP_Error
	 */
	public static function download( $download_url, $version ) {
		$upload = wp_get_upload_dir();
		$dir    = $upload['basedir'] . DIRECTORY_SEPARATOR . 'app-builder';
		if ( ! is_dir( $dir ) ) {
			@mkdir( $dir, 0755 );
		}
		@chmod( $dir, 0755 );

		// If the function it's not available, require it.
		if ( ! function_exists( 'download_url' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		WP_Filesystem();

		$dir_ver = $dir . DIRECTORY_SEPARATOR . $version;

		if ( is_dir( $dir_ver ) ) {

			$it    = new \RecursiveDirectoryIterator( $dir_ver, \RecursiveDirectoryIterator::SKIP_DOTS );
			$files = new \RecursiveIteratorIterator( $it,
				\RecursiveIteratorIterator::CHILD_FIRST );
			foreach ( $files as $file ) {
				if ( $file->isDir() ) {
					@rmdir( $file->getRealPath() );
				} else {
					@unlink( $file->getRealPath() );
				}
			}

			@rmdir( $dir_ver );

		}

		$tmp_file = download_url( $download_url );

		$unzip = unzip_file( $tmp_file, $dir_ver );
		@unlink( $tmp_file );

		if ( is_wp_error( $unzip ) ) {
			return $unzip;
		}

		return true;
	}

	/**
	 * Convert mysql datetime to PHP timestamp, forcing UTC. Wrapper for strtotime.
	 *
	 * Based on wcs_strtotime_dark_knight() from WC Subscriptions by Prospress.
	 *
	 * @param string $time_string Time string.
	 * @param int|null $from_timestamp Timestamp to convert from.
	 *
	 * @return int
	 * @since  1.0.0
	 */
	public static function string_to_timestamp( $time_string, $from_timestamp = null ) {
		$original_timezone = date_default_timezone_get();

		// @codingStandardsIgnoreStart
		date_default_timezone_set( 'UTC' );

		if ( null === $from_timestamp ) {
			$next_timestamp = strtotime( $time_string );
		} else {
			$next_timestamp = strtotime( $time_string, $from_timestamp );
		}

		date_default_timezone_set( $original_timezone );

		// @codingStandardsIgnoreEnd

		return $next_timestamp;
	}

	/**
	 *
	 * Check vendor plugin active
	 *
	 * @return string
	 * @since 1.0.11
	 */
	public static function vendorActive(): string {
		if ( class_exists( 'WeDevs_Dokan' ) || class_exists( 'Dokan_Pro' ) ) {
			return 'dokan';
		}

		if ( class_exists( 'WCFMmp' ) ) {
			return 'wcfm';
		}

		if ( class_exists( 'WCMp' ) ) {
			return 'wcmp';
		}

		if ( class_exists( 'WC_Product_Vendors' ) ) {
			return 'wc_pv';
		}

		if ( class_exists( 'WooCommerce' ) ) {
			return 'single';
		}

		return 'blog';
	}

	/**
	 * Convert currency
	 *
	 * @param $price
	 * @param $currency
	 *
	 * @return mixed
	 */
	public static function convert_currency( $price, $currency ) {

		$defaultCurrency = get_option( 'woocommerce_currency' ) ? get_option( 'woocommerce_currency' ) : 'USD';

		if ( class_exists( 'WCML_Multi_Currency_Prices' ) ) {

			global $woocommerce_wpml;
			if ( $defaultCurrency == $currency || empty( $currency ) || empty( $woocommerce_wpml->multi_currency ) || empty( $woocommerce_wpml->settings['currencies_order'] ) ) {
				return $price;
			}

			return $woocommerce_wpml->multi_currency->prices->raw_price_filter( $price, $currency );
		}

		if ( function_exists( 'wmc_get_price' ) ) {
			return wmc_get_price( $price, $currency );
		}

		return $price;
	}
}
