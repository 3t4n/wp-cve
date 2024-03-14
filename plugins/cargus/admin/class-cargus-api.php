<?php
/**
 * The api calls integrations functionality of the plugin.
 *
 * @link       https://cargus.ro/
 * @since      1.0.0
 *
 * @package    Cargus
 * @subpackage Cargus/admin
 */

if ( ! class_exists( 'Cargus_Api' ) ) {
	/**
	 * The api calls integrations functionality of the plugin.
	 *
	 * Defines the plugin api calls method.
	 *
	 * @package    Cargus
	 * @subpackage Cargus/admin
	 * @author     Cargus <contact@cargus.ro>
	 */
	#[AllowDynamicProperties]
	class Cargus_Api {

		/**
		 * The api key of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $key    The api key of this plugin.
		 */
		private $key;

		/**
		 * The api url of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $url    The api url of this plugin.
		 */
		private $url;

		/**
		 * The api username of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $username    The api username of this plugin.
		 */
		private $username;

		/**
		 * The api password of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $password    The api password of this plugin.
		 */
		private $password;

		/**
		 * The api login token.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $password    The api password of this plugin.
		 */
		public $token;

		/**
		 * Set the api key and url of the plugin.
		 *
		 * @since 1.0.0
		 * @param string $url       The api url of this plugin.
		 * @param string $key       The api key of this plugin.
		 */
		public function __construct( $url = '', $key = '' ) {
			$this->url = ( '' !== $url ) ? $url : get_option( 'woocommerce_cargus_settings' )['webservice'];
			$this->key = ( '' !== $key ) ? $key : get_option( 'woocommerce_cargus_settings' )['apikey'];

			if ( get_option( 'cargus_login_token' ) && 'error' !== get_option( 'cargus_login_token' ) && ! is_object( get_option( 'cargus_login_token' ) ) ) {
				$this->login_user();
			}
		}

		/**
		 * Get the api key.
		 */
		public function get_api_key() {
			return $this->key;
		}

		/**
		 * Get the api key.
		 */
		public function get_url() {
			return $this->url;
		}

		/**
		 * Call a specific api method.
		 *
		 * @since  1.0.0
		 * @param  string $function       The the name of the api method.
		 * @param  string $method         The method of the api method.
		 * @param  string $parameters     The paramethers that are being sent.
		 * @param  bool   $token          The login token.
		 * @throws Exception Exception message.
		 */
		public function call_method( $function, $method, $parameters = '', $token = null ) {
			$url = $this->url . '/' . $function;

			$args = array();
			if ( 'LoginUser' === $function ) {
				$args = array(
					'headers' => array(
						'Ocp-Apim-Subscription-Key' => $this->key,
						'Ocp-Apim-Trace'            => true,
						'Content-Type'              => 'application/json',
						'ContentLength'             => strlen( wp_json_encode( $parameters ) ),
					),
				);
			} else {
				$args = array(
					'headers' => array(
						'Ocp-Apim-Subscription-Key' => $this->key,
						'Ocp-Apim-Trace'            => true,
						'Authorization'             => 'Bearer ' . $token,
						'Content-Type'              => 'application/json',
					),
				);

				if ( 'Awbs' === $function && 'POST' === $method ) {
					$args['headers']['Path'] = 'WP' . substr( $this->get_cargus_get_woocommerce_version(), 0, 3 );
				}
			}

			switch ( $method ) {
				case 'POST':
					if ( ! empty( $parameters ) ) {
						$args['body'] = wp_json_encode( $parameters );
					}

					$response = wp_remote_post( $url, $args );
					break;

				case 'DELETE':
					if ( ! empty( $parameters ) ) {
						$args['body'] = wp_json_encode( $parameters );
					}
					$args['method'] = 'DELETE';
					$response       = wp_remote_request( $url, $args );
					break;

				case 'PUT':
					if ( ! empty( $parameters ) ) {
						$args['body'] = wp_json_encode( $parameters );
					}
					$args['method'] = 'PUT';
					$response       = wp_remote_request( $url, $args );
					break;

				case 'GET':
					$query_url = $url;
					if ( ! empty( $parameters ) ) {
						$query_url = $url . '?' . http_build_query( $params );
					}
					$response = wp_remote_get( $query_url, $args );
					break;

				default:
					throw new Exception( 'Unknown method used' );
			}

			if ( ! is_wp_error( $response ) ) {
				$code = wp_remote_retrieve_response_code( $response );
				$body = json_decode( $response['body'] );

				if ( '200' === $code ) {
					if ( is_array( $body ) && isset( $body['message'] ) ) {
						return $body['message'];
					} else {
						return $body;
					}
				} elseif ( '500' === $code ) {
					return array(
						'statusCode' => 500,
						'message'    => $body,
					);
				} else {
					return $body;
				}
			}
		}

		/**
		 * Get the installed woocommerce version.
		 *
		 * @since    1.0.0
		 */
		private function get_cargus_get_woocommerce_version() {
			// If get_plugins() isn't available, require it.
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			// Create the plugins folder and file variables.
			$plugin_folder = get_plugins( '/woocommerce' );
			$plugin_file   = 'woocommerce.php';

			// If the plugin version number is set, return it.
			if ( isset( $plugin_folder[ $plugin_file ]['Version'] ) ) {
				return $plugin_folder[ $plugin_file ]['Version'];
			} else {
				// Otherwise return null.
				return null;
			}
		}

		/**
		 * Call cargus Login endpoint endpoint.
		 *
		 * @param  array $fields       The api login token.
		 * @param  bool  $generate     To generate or not a new login token.
		 */
		public function login_user( $fields = array(), $generate = false ) {
			$this->username = ( isset( $fields['UserName'] ) ) ? $fields['UserName'] : '';
			$this->password = ( isset( $fields['Password'] ) ) ? $fields['Password'] : '';

			if ( get_option( 'cargus_login_token' ) && 'error' !== get_option( 'cargus_login_token' ) && ! is_object( get_option( 'cargus_login_token' ) ) && ! $generate ) {
				$this->token = get_option( 'cargus_login_token' );
			} elseif ( $generate ) {
				// generate new token.
				$token = $this->call_method( 'LoginUser', 'POST', $fields );
				if ( 'error' !== $token && ! is_object( $token ) && ! is_array( $token ) ) {
					// save new token if valid.
					$this->token = $token;
					update_option( 'cargus_login_token', $token, false );
				}
			} else {
				// generate new token.
				$token = $this->call_method( 'LoginUser', 'POST', $fields );
				if ( 'error' !== $token && ! is_object( $token ) && ! is_array( $token ) ) {
					// save new token if valid.
					$this->token = $token;
					update_option( 'cargus_login_token', $token, false );
				}
			}

			return $this->token;
		}

		/**
		 * Call cargus pickup locations api endpoint.
		 */
		public function get_pickup_locations() {
			$json = array();
			$json = $this->call_method( 'PickupLocations', 'GET', array(), $this->token );

			return $json;
		}

		/**
		 * Call cargus pricetables api endpoint.
		 */
		public function get_price_tables() {
			$json = array();
			$json = $this->call_method( 'PriceTables', 'GET', array(), $this->token );

			return $json;
		}

		/**
		 * Call cargus shipping calculation api endpoint.
		 *
		 * @param array $fields The shipping calculation fields.
		 */
		public function get_shipping_calulation( $fields ) {
			$json = array();
			$json = $this->call_method( 'ShippingCalculation', 'POST', $fields, $this->token );

			return $json;
		}

		/**
		 * Call cargus pudo points api endpoint.
		 */
		public function get_pudo_points() {
			$json = array();
			$json = $this->call_method( 'PudoPoints', 'GET', array(), $this->token );

			return $json;
		}

		/**
		 * Call cargus Streets api endpoint.
		 *
		 * @param int $locality_id The locality id.
		 */
		public function get_streets( $locality_id ) {
			// get streets.
			if ( is_null( $locality_id ) ) {
				return array();
			}

			$json = array();
			// get api json.
			// Streets?locality_id=97243.
			$json = $this->call_method( 'Streets?localityId=' . $locality_id, 'GET', array(), $this->token );

			if ( ! $json ) {
				// get old cache if exists.
				$cache = new Cargus_Cache();

				$temp = $cache->get_cached_file( 'str' . $locality_id, true );
				if ( false !== $temp ) {
					// return the old data.
					$json = json_decode( $temp, true );
				}
			}

			return $json;
		}

		/**
		 * Call cargus localities api endpoint.
		 *
		 * @param int $county_id The conty id.
		 */
		public function get_localities( $county_id ) {
			// get streets.
			if ( is_null( $county_id ) ) {
				return array();
			}

			$json = array();
			// get api json.
			// Localities?countryId=1&countyId=97243.
			$json = $this->call_method( 'Localities?countryId=1&countyId=' . $county_id, 'GET', array(), $this->token );

			if ( ! $json ) {
				// get old cache if exists.
				$cache = new Cargus_Cache();

				$temp = $cache->get_cached_file( 'localities' . $county_id, true );
				if ( false !== $temp ) {
					// return the old data.
					$json = json_decode( $temp, true );
				}
			}

			return $json;
		}

		/**
		 * Call cargus counties api endpoint.
		 */
		public function get_counties() {
			$json = array();

			// get api json.
			// Counties?countryId=1.
			$json = $this->call_method( 'Counties?countryId=1', 'GET', array(), $this->token );
			if ( ! $json ) {
				// get old cache if exists.
				$cache = new Cargus_Cache();
				$temp  = $cache->getCacheFile( 'counties', true );
				if ( false !== $temp ) {
					// return the old data.
					$json = json_decode( $temp, true );
				}
			}

			return $json;
		}

		/**
		 * Call cargus pickup locations api endpoint.
		 */
		public function get_shipment_status( $awb ) {
			$json = array();
			$json = $this->call_method( 'AwbStatus/GetAwbSyncStatusByBarCode?barCode=' . $awb , 'GET', array(), $this->token );

			return $json;
		}
	}
}
