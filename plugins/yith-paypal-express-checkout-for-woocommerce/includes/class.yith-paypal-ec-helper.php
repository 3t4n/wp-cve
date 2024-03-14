<?php //phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH PayPal Express Checkout for WooCommerce
 * @since  1.0.0
 * @author YITH <plugins@yithemes.com>
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly,


if ( ! class_exists( 'YITH_PayPal_EC_Helper' ) ) {
	/**
	 * Class YITH_PayPal_EC_Helper
	 */
	class YITH_PayPal_EC_Helper {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_PayPal_EC_Helper
		 */
		protected static $instance;

		/**
		 * Settings
		 *
		 * @var array
		 */
		protected $settings;

		/**
		 * Logger
		 *
		 * @var WC_Logger
		 */
		public $log;

		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.0.0
		 */
		public function __construct() {
			$this->settings = get_option( 'woocommerce_yith-paypal-ec_settings', array() );

			$this->load_settings();

			$this->log = new WC_Logger();
		}

		/**
		 * Load the parameter settings adding other information.
		 *
		 * @since 1.0.0
		 */
		private function load_settings() {
			$this->settings['enabled']       = isset( $this->settings['enabled'] ) && '' !== $this->settings['enabled'] ? $this->settings['enabled'] : 'no';
			$this->settings['env']           = isset( $this->settings['env'] ) && 'no' !== $this->settings['enabled'] ? $this->settings['env'] : 'sandbox';
			$this->settings['api_username']  = isset( $this->settings[ $this->settings['env'] . '_api_username' ] ) ? $this->settings[ $this->settings['env'] . '_api_username' ] : '';
			$this->settings['api_password']  = isset( $this->settings[ $this->settings['env'] . '_api_password' ] ) ? $this->settings[ $this->settings['env'] . '_api_password' ] : '';
			$this->settings['api_signature'] = isset( $this->settings[ $this->settings['env'] . '_api_signature' ] ) ? $this->settings[ $this->settings['env'] . '_api_signature' ] : '';
			$this->settings['api_subject']   = isset( $this->settings[ $this->settings['env'] . '_api_subject' ] ) ? $this->settings[ $this->settings['env'] . '_api_subject' ] : '';
			$this->settings['api_endpoint']  = ( 'sandbox' === $this->settings['env'] ) ? 'https://api-3t.sandbox.paypal.com/nvp' : 'https://api-3t.paypal.com/nvp';
		}

		/**
		 * Creates or updates a property in the object.
		 *
		 * @param string $key Key.
		 * @param mixed  $value Value.
		 * @since 1.0.0
		 */
		public function __set( $key, $value ) {
			if ( array_key_exists( $key, $this->settings ) ) {
				$this->settings[ $key ] = $value;
			}
		}

		/**
		 * Returns a property of the object.
		 *
		 * @param string $key Key.
		 *
		 * @return mixed|null
		 * @since 1.0.0
		 */
		public function __get( $key ) {
			if ( array_key_exists( $key, $this->settings ) ) {
				return $this->settings[ $key ];
			}
			return null;
		}

		/**
		 * Checks if a given key exists in our data. This is called internally
		 * by `empty` and `isset`.
		 *
		 * @param string $key Key.
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function __isset( $key ) {
			return array_key_exists( $key, $this->settings );
		}

		/**
		 * Add message in log if debug is enabled.
		 *
		 * @param string $message Message.
		 * @since 1.0.0
		 */
		public function log_add_message( $message ) {
			if ( 'yes' !== $this->log_enabled ) {
				return;
			}

			$this->log->add( 'yith_paypal_ec', $message );
		}

		/**
		 * Limit length of an arg.
		 *
		 * @param  string  $string String.
		 * @param  integer $limit Limit.
		 * @return string
		 */
		public static function format_item_name( $string, $limit = 127 ) {
			if ( strlen( $string ) > $limit ) {
				$string = substr( $string, 0, $limit - 3 ) . '...';
			}
			return wp_strip_all_tags( $string );
		}

		/**
		 * Return the short description of an order item
		 *
		 * @param WC_Order_Item $order_item Order item.
		 *
		 * @return mixed
		 */
		public static function get_order_item_description( $order_item ) {
			$item_desc = array();
			foreach ( $order_item->get_formatted_meta_data() as $meta ) {
				$item_desc[] = sprintf( '%s: %s', $meta->display_key, $meta->display_value );
			}
			$item_desc = implode( ',', (array) $item_desc );

			return apply_filters( 'yith_paypal_ec_get_order_item_description', self::format_item_name( $item_desc ), $order_item );
		}

		/**
		 * Checks if the PayPal API credentials are set.
		 *
		 * @since 1.0
		 */
		public function valid_api_settings() {
			$is_valid = false;
			if ( '' !== $this->api_username && '' !== $this->api_password && '' !== $this->api_signature ) {
				$is_valid = true;
			}
			return apply_filters( 'yith_paypal_ec_valid_api_settings', $is_valid );
		}

		/**
		 * Checks if this gateway is enabled and available in the user's country.
		 *
		 * @return bool
		 *
		 * @since 1.0
		 */
		public function is_valid_for_use() {
			return in_array( get_woocommerce_currency(), apply_filters( 'yith_paypal_ec_supported_currencies', array( 'AUD', 'BRL', 'CAD', 'MXN', 'NZD', 'HKD', 'SGD', 'USD', 'EUR', 'JPY', 'TRY', 'NOK', 'CZK', 'DKK', 'HUF', 'ILS', 'MYR', 'PHP', 'PLN', 'SEK', 'CHF', 'TWD', 'THB', 'GBP', 'RMB', 'RUB', 'INR' ) ), true );
		}

		/**
		 * Gets PayPal images for a country.
		 *
		 * @param string $country Country code.
		 * @return array of image URLs
		 *
		 * @since 1.0
		 */
		protected function get_icon_image( $country ) {
			switch ( $country ) {
				case 'US':
				case 'NZ':
				case 'CZ':
				case 'HU':
				case 'MY':
					$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg';
					break;
				case 'TR':
					$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_paypal_odeme_secenekleri.jpg';
					break;
				case 'GB':
					$icon = 'https://www.paypalobjects.com/webstatic/mktg/Logo/AM_mc_vs_ms_ae_UK.png';
					break;
				case 'MX':
					$icon = array(
						'https://www.paypal.com/es_XC/Marketing/i/banner/paypal_visa_mastercard_amex.png',
						'https://www.paypal.com/es_XC/Marketing/i/banner/paypal_debit_card_275x60.gif',
					);
					break;
				case 'FR':
					$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_paypal_moyens_paiement_fr.jpg';
					break;
				case 'AU':
					$icon = 'https://www.paypalobjects.com/webstatic/en_AU/mktg/logo/Solutions-graphics-1-184x80.jpg';
					break;
				case 'DK':
					$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_PayPal_betalingsmuligheder_dk.jpg';
					break;
				case 'RU':
					$icon = 'https://www.paypalobjects.com/webstatic/ru_RU/mktg/business/pages/logo-center/AM_mc_vs_dc_ae.jpg';
					break;
				case 'NO':
					$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/banner_pl_just_pp_319x110.jpg';
					break;
				case 'CA':
					$icon = 'https://www.paypalobjects.com/webstatic/en_CA/mktg/logo-image/AM_mc_vs_dc_ae.jpg';
					break;
				case 'HK':
					$icon = 'https://www.paypalobjects.com/webstatic/en_HK/mktg/logo/AM_mc_vs_dc_ae.jpg';
					break;
				case 'SG':
					$icon = 'https://www.paypalobjects.com/webstatic/en_SG/mktg/Logos/AM_mc_vs_dc_ae.jpg';
					break;
				case 'TW':
					$icon = 'https://www.paypalobjects.com/webstatic/en_TW/mktg/logos/AM_mc_vs_dc_ae.jpg';
					break;
				case 'TH':
					$icon = 'https://www.paypalobjects.com/webstatic/en_TH/mktg/Logos/AM_mc_vs_dc_ae.jpg';
					break;
				case 'JP':
					$icon = 'https://www.paypal.com/ja_JP/JP/i/bnr/horizontal_solution_4_jcb.gif';
					break;
				case 'IN':
					$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg';
					break;
				default:
					$icon = WC_HTTPS::force_https_url( WC()->plugin_url() . '/includes/gateways/paypal/assets/images/paypal.png' );
					break;
			}
			return apply_filters( 'woocommerce_paypal_icon', $icon );
		}

		/**
		 * Gets the link for an icon based on country.
		 *
		 * @param  string $country Country.
		 * @return string
		 *
		 * @since 1.0
		 */
		protected function get_icon_url( $country ) {
			$url           = 'https://www.paypal.com/' . strtolower( $country );
			$home_counties = array( 'BE', 'CZ', 'DK', 'HU', 'IT', 'JP', 'NL', 'NO', 'ES', 'SE', 'TR', 'IN' );
			$countries     = array( 'DZ', 'AU', 'BH', 'BQ', 'BW', 'CA', 'CN', 'CW', 'FI', 'FR', 'DE', 'GR', 'HK', 'ID', 'JO', 'KE', 'KW', 'LU', 'MY', 'MA', 'OM', 'PH', 'PL', 'PT', 'QA', 'IE', 'RU', 'BL', 'SX', 'MF', 'SA', 'SG', 'SK', 'KR', 'SS', 'TW', 'TH', 'AE', 'GB', 'US', 'VN' );

			if ( in_array( $country, $home_counties, true ) ) {
				return $url . '/webapps/mpp/home';
			} elseif ( in_array( $country, $countries, true ) ) {
				return $url . '/webapps/mpp/paypal-popup';
			} else {
				return $url . '/cgi-bin/webscr?cmd=xpt/Marketing/general/WIPaypal-outside';
			}
		}

		/**
		 * Gets gateway icon.
		 *
		 * @return string
		 *
		 * @since 1.0
		 */
		public function get_icon() {
			$icon_html = '<br>';
			$icon      = (array) $this->get_icon_image( WC()->countries->get_base_country() );

			foreach ( $icon as $i ) {
				$icon_html .= '<img src="' . esc_attr( $i ) . '" alt="' . esc_attr__( 'PayPal acceptance mark', 'yith-paypal-express-checkout-for-woocommerce' ) . '" />';
			}

			$icon_html .= sprintf( '<a href="%1$s" class="about_paypal" onclick="javascript:window.open(\'%1$s\',\'WIPaypal\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700\'); return false;">' . esc_attr__( 'What is PayPal?', 'yith-paypal-express-checkout-for-woocommerce' ) . '</a>', esc_url( $this->get_icon_url( WC()->countries->get_base_country() ) ) );

			return apply_filters( 'yith_paypal_ec_gateway_icon', $icon_html, $this->id );
		}

		/**
		 * Round a float
		 *
		 * @param float $number Number to round.
		 * @param int   $precision Optional. The number of decimal digits to round to.
		 *
		 * @return float
		 *
		 * @since 1.0
		 */
		public static function round( $number, $precision = 2 ) {
			return round( (float) $number, $precision );
		}

		/**
		 * Get PayPal redirect URL.
		 *
		 * @param string $token Token.
		 * @param bool   $commit Add commit.
		 *
		 * @return string
		 *
		 * @since 1.0
		 */
		public function get_paypal_redirect_url( $token, $commit = false ) {
			$url = 'https://www.';

			if ( 'live' !== $this->env ) {
				$url .= 'sandbox.';
			}

			$url .= 'paypal.com/checkoutnow?token=' . rawurlencode( $token );

			if ( $commit ) {
				$url .= '&useraction=commit';
			}

			return $url;
		}

		/**
		 * Unset the session of a transaction.
		 *
		 * @return void
		 * @since 1.0
		 */
		public function clear_session() {
			unset( WC()->session->yith_paypal_session );
		}
	}
}

return new YITH_PayPal_EC_Helper();
