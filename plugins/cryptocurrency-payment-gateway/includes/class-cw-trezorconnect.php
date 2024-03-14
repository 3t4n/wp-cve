<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}// Exit if accessed directly

class CW_TrezorConnect {


	/**
	 * Check if a coin is supported by Trezor
	 *
	 * @param string $currency The cryptocurrency symbol, e.g. BTC.
	 *
	 * @return bool
	 */
	public static function is_supported_coin( $currency ) : bool {
		return in_array( strtoupper( $currency ), self::get_supported_coins(), true );
	}

	/**
	 * Check if a coin is supported by Trezor
	 *
	 * @param string $currency The cryptocurrency symbol, e.g. BTC.
	 *
	 * @return bool
	 */
	public static function is_supported_bitcoin_like_coin( $currency ) : bool {
		return in_array( strtoupper( $currency ), self::get_supported_bitcoin_like_coins(), true );
	}

	/**
	 * Return connect button HTML
	 *
	 * @param string $currency The cryptocurrency symbol, e.g. BTC.
	 *
	 * @return string
	 */
	public static function get_connect_button( $currency ) : string {
		$html = '';
		if ( self::is_supported_coin( $currency ) ) {
			$style = 'display: none; padding: 0.2em; border: 1px solid black;border-radius: 2px;';
			$html  = '<br><div class="trezor-connect-log" id="trezor-connect-log-%1$s"%2$s></div><div class="button" id="cwhd-connect-trezor-%1$s" style="color: black; border-color: black;"><i class="cw-coin-trezor-logo"></i></div>';
			$html  = sprintf( $html, strtolower( $currency ), $style );
		}

		return $html;
	}

	/**
	 * Return pay button html
	 *
	 * @param string $currency The cryptocurrency symbol, e.g. BTC.
	 *
	 * @return string
	 */
	public static function get_pay_button( $currency ) : string {

		$html = '';
		if ( self::is_supported_bitcoin_like_coin( $currency ) ) {
			$style = 'display: none; padding: 0.2em; border: 1px solid black;border-radius: 2px;';
			$html  = '<div class="trezor-connect-log" id="trezor-connect-log-%1$s"%2$s></div><div class="button" id="cwhd-connect-trezor-%1$s"><i class="cw-coin-trezor-logo"></i></div>';
			$html  = sprintf( $html, strtolower( $currency ), $style );
		}

		return $html;
	}

	/**
	 * Echo pay button
	 *
	 * @param string $currency The cryptocurrency symbol, e.g. BTC.
	 */
	public static function print_pay_button( $currency ) {
		echo wp_kses_post( self::get_pay_button( $currency ) );
	}

	/**
	 * Trezor supported currencies
	 * according to https://github.com/trezor/trezor-suite/blob/develop/packages/connect-common/files/coins.json
	 *
	 * @return string[]
	 */
	public static function get_supported_coins() : array {
		return array(
			'BTC',
			'REGTEST',
			'TEST',
			'ACM',
			'AXE',
			'BCH',
			'TBCH',
			'BTG',
			'TBTG',
			'BTCP',
			'BTX',
			'DASH',
			'tDASH',
			'DCR',
			'TDCR',
			'DGB',
			'DOGE',
			'FTC',
			'FIRO',
			'tFIRO',
			'FLO',
			'FJC',
			'KMD',
			'KOTO',
			'LTC',
			'tLTC',
			'MONA',
			'NMC',
			'PPC',
			'tPPC',
			'XPM',
			'RVN',
			'RITO',
			'XSN',
			'SYS',
			'UNO',
			'XVG',
			'VTC',
			'VIA',
			'ZCR',
			'ZEC',
			'TAZ',
			'XRC',
			'SOL',
			'ADA',
			'BNB',
			'EOS',
			'tADA',
			'tXRP',
			'XLM',
			'XRP',
			'XTZ',
			'DSOL',
			'XEM',
			'DIM',
			'DIMTOK',
			'BREEZE',
			'PAC:HRT',
			'PAC:CHS',
		);
	}

	/**
	 * Trezor supported currencies
	 * according to https://github.com/trezor/trezor-suite/blob/develop/packages/connect-common/files/coins.json
	 *
	 * @return string[]
	 */
	public static function get_supported_bitcoin_like_coins() : array {
		return array(
			'BTC',
			'REGTEST',
			'TEST',
			'ACM',
			'AXE',
			'BCH',
			'TBCH',
			'BTG',
			'TBTG',
			'BTCP',
			'BTX',
			'DASH',
			'tDASH',
			'DCR',
			'TDCR',
			'DGB',
			'DOGE',
			'FTC',
			'FIRO',
			'tFIRO',
			'FLO',
			'FJC',
			'KMD',
			'KOTO',
			'LTC',
			'tLTC',
			'MONA',
			'NMC',
			'PPC',
			'tPPC',
			'XPM',
			'RVN',
			'RITO',
			'XSN',
			'SYS',
			'UNO',
			'XVG',
			'VTC',
			'VIA',
			'ZCR',
			'ZEC',
			'TAZ',
			'XRC',
		);
	}

}
