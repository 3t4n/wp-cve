<?php

namespace InspireLabs\WoocommerceInpost;

use Exception;
use InspireLabs\WoocommerceInpost\admin\Alerts;
use InspireLabs\WoocommerceInpost\shipx\models\courier_pickup\ShipX_Dispatch_Order_Point_Address_Model;
use InspireLabs\WoocommerceInpost\shipx\models\courier_pickup\ShipX_Dispatch_Order_Point_Model;

/**
 * EasyPack API
 *
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'InspireLabs\WoocommerceInpost\EasyPack_API' ) ) :

	class EasyPack_API {

		const API_URL_PRODUCTION_PL = 'https://api-shipx-pl.easypack24.net';

		const API_URL_SANDBOX_PL = 'https://sandbox-api-shipx-pl.easypack24.net';

		const API_URL_PRODUCTION_UK = 'https://api-shipx-uk.easypack24.net/v1/';

		const API_URL_SANDBOX_UK = 'https://sandbox-api-shipx-uk.easypack24.net/v1/';

		const ENVIRONMENT_PRODUCTION = 'production';

		const ENVIRONMENT_SANDBOX = 'sandbox';

		const COUNTRY_PL = 'PL';

		const COUNTRY_UK = 'GB';

		/**
		 * @var self
		 */
		protected static $instance;

		/**
		 * @var string
		 */
		private $environment;

		/**
		 * @var string
		 */
		private $country;

		/**
		 * @var string
		 */
		private $api_url;

		protected $token;

		protected $cache_period = DAY_IN_SECONDS;

        protected $geo_widget_api_url;


		public function __construct() {
			$this->token = get_option( 'easypack_token' );
			$this->setupEnvironment();
		}

		private function setupEnvironment() {
			if ( 'sandbox' === get_option( 'easypack_api_environment' ) ) {
				$this->environment = self::ENVIRONMENT_SANDBOX;
			} else {
				$this->environment = self::ENVIRONMENT_PRODUCTION;
			}

			if ( self::COUNTRY_PL === $this->normalize_country_code_for_inpost(
					get_option( 'easypack_api_country' ) )
			) {
				$this->country = self::COUNTRY_PL;
			}

			if ( self::COUNTRY_UK === $this->normalize_country_code_for_inpost(
					get_option( 'easypack_api_country' )
				)
			) {
				$this->country = self::COUNTRY_UK;
			}

			$this->api_url = $this->make_api_url();
		}

		/**
		 * @param string $country_code
		 *
		 * @return string
		 */
		public function normalize_country_code_for_geowidget( $country_code ) {
			return strtolower( $country_code );
		}

		/**
		 * @param string $country_code
		 *
		 * @return string
		 */
		public function normalize_country_code_for_inpost( $country_code ) {
			return strtoupper( $country_code );
		}

		/**
		 * @return bool
		 */
		public function is_sandbox_env() {
			return self::ENVIRONMENT_SANDBOX === $this->environment;
		}

		/**
		 * @return bool
		 */
		public function is_production_env() {
			return self::ENVIRONMENT_PRODUCTION === $this->environment;
		}

		/**
		 * @return bool
		 */
		public function is_uk() {
			return self::COUNTRY_UK === $this->country;
		}

		/**
		 * @return bool
		 */
		public function is_pl() {
			return self::COUNTRY_PL === $this->country;
		}

		public static function EasyPack_API() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * @param null $url
		 *
		 * @return null|string
		 */
		public function make_api_url( $url = null ) {

			if ( self::ENVIRONMENT_SANDBOX === $this->environment ) {
				if ( self::COUNTRY_PL === $this->country ) {
					$url = self::API_URL_SANDBOX_PL;
					$this->geo_widget_api_url
					     = 'https://sandbox-api-pl-points.easypack24.net/v1';
				}

				if ( self::COUNTRY_UK === $this->country ) {
					$url = self::API_URL_SANDBOX_UK;
					$this->geo_widget_api_url
					     = 'https://sandbox-api-uk-points.easypack24.net/v1';
				}
			}

			if ( self::ENVIRONMENT_PRODUCTION === $this->environment ) {
				if ( self::COUNTRY_PL === $this->country ) {
					$url = self::API_URL_PRODUCTION_PL;
					$this->geo_widget_api_url
					     = 'https://api-pl-points.easypack24.net/v1';
				}

				if ( self::COUNTRY_UK === $this->country ) {
					$url = self::API_URL_PRODUCTION_UK;
					$this->geo_widget_api_url
					     = 'https://api-uk-points.easypack24.net/v1';
				}
			}

			$url = untrailingslashit( $url );
			$parsed_url = parse_url( $url );
			if ( ! isset( $parsed_url['path'] ) || trim( $parsed_url['path'] ) == '' ) {
				$url .= '/v1';
			}

			return $url;
		}

		public function clear_cache() {
			$this->token   = get_option( 'easypack_token' );
			$this->api_url = $this->make_api_url();
		}

		function translate_error( $error ) {
			$errors = [
				'receiver_email'     => __( 'Recipient e-mail', 'woocommerce-inpost' ),
				'forbidden'          => __( 'forbidden' ),
                'receiver_phone'     => __( 'Recipient phone', 'woocommerce-inpost' ),
				'address'            => __( 'Address', 'woocommerce-inpost' ),
				'phone'              => __( 'Phone', 'woocommerce-inpost' ),
				'email'              => __( 'Email', 'woocommerce-inpost' ),
				'post_code'          => __( 'Post code', 'woocommerce-inpost' ),
				'postal_code'        => __( 'Post code', 'woocommerce-inpost' ),
				'default_machine_id' => __( 'Default parcel locker', 'woocommerce-inpost' ),

				'not_an_email'             => __( 'not valid', 'woocommerce-inpost' ),
				'invalid'                  => __( 'invalid', 'woocommerce-inpost' ),
				'not_found'                => __( 'not found', 'woocommerce-inpost' ),
				'invalid_format'           => __( 'invalid format', 'woocommerce-inpost' ),
				'required, invalid_format' => __( 'required', 'woocommerce-inpost' ),
				'too_many_characters'      => __( 'too many characters', 'woocommerce-inpost' ),
                'Action (cancel) can not be taken on shipment with status (confirmed).'
				                           => __( 'Action (cancel) can not be taken on shipment with status (confirmed).', 'woocommerce-inpost' ),
				'There are some validation errors. Check details object for more info.'
				                           => __( 'There are some validation errors.', 'woocommerce-inpost' ),

				'Access to this resource is forbidden'        => __( 'Invalid login or token', 'woocommerce-inpost' ),
				'Sorry, access to this resource is forbidden' => __( 'Invalid login', 'woocommerce-inpost' ),
				'Token is missing or invalid.' => __( 'Token is missing or invalid. Or service works on server, try later.', 'woocommerce-inpost' ),
				'Box machine name cannot be empty' => __( 'Parcel Locker is empty. Please fill in this field.', 'woocommerce-inpost' ),
				'Default parcel machine' => __( 'Default send parcel locker: ', 'woocommerce-inpost' ),
				'The transaction can not be completed due to the balance of your account' => __( 'The transaction can not be completed due to the balance of your account',
                    'woocommerce-inpost' ),
                'You have not enough funds to pay for this parcel' => __( 'Can not create sticker. You have not enough funds to pay for this parcel',
                    'woocommerce-inpost' )
			];

			if ( isset( $errors[ $error ] ) ) {
				return $errors[ $error ];
			}

			return $error;
		}

		public function get_error( $errors ) {
			return $this->get_error_recursive( $errors, 10 );
		}

		/**
		 * @param      $array
		 * @param int $level
		 * @param null $key_recursive
		 *
		 * @return string
		 */
		private function get_error_recursive(
			$array,
			$level = 1,
			$key_recursive = null
		) {

			$output = '';
			if ( null !== $key_recursive
			     && ! is_numeric( $key_recursive )
			) {
				$output .= $key_recursive . ' ';
			}
			foreach ( $array as $key => $value ) {

				if ( is_array( $value ) ) {

					$output .= $this->get_error_recursive( $value, $level + 1,
						$key );
				} else {
					if ( ! is_numeric( $key ) ) {
						$value  = str_replace( '_', ' ', $value );
						$output .= $key . ': ' . $value . '<br>';
					} else {
						if ( ! is_array( $value ) ) {
							$output .= $value . '<br>';
						}
					}
				}
			}

			return $output;
		}

		/**
		 * @param array $response
		 *
		 * @return bool
		 */
		private function is_binary_response( $response ) {
			if ( ! isset( $response['headers']['content-transfer-encoding'] ) ) {
				return false;
			}

			$headers = $response['headers'];
			$data    = $headers->getAll();

			return $data['content-transfer-encoding'] === 'binary';
		}


		public function post( $path, $args = [], $method = 'POST' ) {
			$url = untrailingslashit( $this->api_url ) . str_replace( ' ', '%20', str_replace( '@', '%40', $path ) );
			$request_args = [ 'timeout' => 30, 'method' => $method ];

			$request_args['headers'] = [
				'Authorization' => 'Bearer ' . $this->token,
				'Content-Type'  => 'application/json',
			];

			$request_args['body'] = $args;
			$request_args['body'] = json_encode( $args );

			$response = wp_remote_post( $url, $request_args );

			if ( defined( 'SHIPX_API_LOG' ) ) {
				$this->addToLog(
					'POST',
					$url,
					$request_args,
					$response
				);
			}

			if ( is_wp_error( $response ) ) {
				throw new Exception( $response->get_error_message() );
			} else {

				if ( $this->is_binary_response( $response ) ) {
					return [
						'headers' => $response['headers'],
						'body' => $response['body'],
					];
				}

				$ret = json_decode( $response['body'], true );
				if ( ! is_array( $ret ) ) {
					throw new Exception( __( 'Bad API response. Check API URL', 'woocommerce-inpost' ), 503 );
				} else {
					if ( isset( $ret['status'] ) ) {
						$errors = '';
						if ( isset( $ret['error'] ) && ! empty( $ret['error'] ) ) {
							if ( is_array( $ret['details'] ) ) {
								if ( count( $ret['details'] ) ) {
									$errors = $this->get_error( $ret['details'] );
								}
							} else {
								$errors = ': ' . $ret['details'];
							}
						} else {
							if ( isset( $ret['message'] ) ) {
								$errors = $this->translate_error( $ret['message'] );
							}
						}
						if ( isset( $ret['errors'] ) || isset( $ret['error'] ) ) {
							if ( empty( $errors ) ) {
								$errors = $ret['message'];
							}
							throw new Exception( str_replace( '_', ' ', $errors ), $ret['status'] );
						}
					}
				}

				return $ret;
			}

		}


		/**
		 * @param        $path
		 * @param array $args
		 * @param string $method
		 *
		 * @return array|mixed|object
		 * @throws Exception
		 */
		public function delete( $path, $args = [], $method = 'DELETE' ) {
			$url = untrailingslashit( $this->api_url ) . str_replace( ' ', '%20', str_replace( '@', '%40', $path ) );
			$request_args = [ 'timeout' => 30, 'method' => $method ];

			$request_args['headers'] = [
				'Authorization' => 'Bearer ' . $this->token,
				'Content-Type'  => 'application/json',
			];

			$request_args['body'] = $args;
			$request_args['body'] = json_encode( $args );

			$response = wp_remote_post( $url, $request_args );
			if ( is_wp_error( $response ) ) {
				throw new Exception( $response->get_error_message() );
			} else {

				if ( $this->is_binary_response( $response ) ) {
					return [
						'headers' => $response['headers'],
						'body'    => $response['body'],
					];
				}

				$ret = json_decode( $response['body'], true );
				if ( ! is_array( $ret ) ) {
					throw new Exception( __( 'Bad API response. Check API URL', 'woocommerce-inpost' ), 503 );
				} else {
					if ( isset( $ret['status'] ) ) {
						$errors = '';

						if ( isset( $ret['error'] ) && is_array( $ret['error'] ) && count( $ret['error'] ) ) {
							if ( ! empty( $ret['message'] ) ) {
								$errors = $this->translate_error( $ret['message'] );
								throw new Exception( $errors, $ret['status'] );
							}
							if ( is_array( $ret['details'] ) ) {
								if ( count( $ret['details'] ) ) {
									$errors = $this->get_error( $ret['details'] );
								}
							} else {
								$errors = ': ' . $ret['details'];
							}
						} else {
							$errors = $this->translate_error( $ret['message'] );
						}
						if ( isset( $ret['errors'] ) || isset( $ret['error'] ) ) {
							if ( empty( $errors ) ) {
								$errors = $ret['message'];
							}
							throw new Exception( $errors, $ret['status'] );
						}
					}
				}

				return $ret;
			}

		}


		public function put( $path, $args = [], $method = 'PUT' ) {
			return $this->post( $path, $args, 'PUT' );
		}

		public function get(
			$path,
			$args = [],
			$params = [],
			$decode_url = false
		) {

		    $ret = null;

		    $url = untrailingslashit( $this->api_url ) . str_replace( ' ',	'%20',	str_replace( '@', '%40', $path ) );

			if ( ! empty( $params ) ) {
				$newUrl = $url;
				foreach ( $params as $k => $v ) {
					$newUrl = add_query_arg( $k, $v, $newUrl );
				}

				if ( true === $decode_url ) {
					$url = preg_replace( '/\[[^\[\]]*\]/', '[]', urldecode( $newUrl ) );
				} else {
					$url = $newUrl;
				}
			}

            if ( ! empty( $this->token ) ) {

                $request_args = [ 'timeout' => 30 ];

                $request_args['headers'] = [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Content-Type'  => 'application/json',
                ];

                $response = wp_remote_get( $url, $request_args );

                if ( defined( 'SHIPX_API_LOG' ) ) {
                    $this->addToLog(
                        'GET',
                        $url,
                        $request_args,
                        $response
                    );
                }

                if ( is_wp_error( $response ) ) {
                    $this->authorizationError( $response->get_error_message() . ' ( Endpoint: ' . $url . ' )', $response->get_error_code() );
                } else {

                    if ($this->is_binary_response($response)) {
                        return [
                            'headers' => $response['headers'],
                            'body' => $response['body'],
                        ];
                    }

                    $ret = json_decode($response['body'], true);
                    if ( ! is_array( $ret ) ) {
                        //throw new Exception(__( 'Bad API response. Check API URL', 'woocommerce-inpost' ), 503 );

                        $alerts = new Alerts();
                        $alerts->add_error( 'InPost PL: ' . __( 'Bad API response', 'woocommerce-inpost' ) );

                    } else {
                        if ( isset( $ret['status'] ) ) {
                            $errors = '';
                            if ( isset( $ret['error'] ) && ! empty( $ret['error'] ) ) {
                                if ( is_array( $ret['details'] ) ) {
                                    if ( count( $ret['details'] ) ) {
                                        $errors = $this->get_error( $ret['details'] );
                                    }
                                } else {
                                    if ( ! empty( $ret['details'] ) ) {
                                        $errors = ': ' . $ret['details'];
                                    }

                                    if ( ! empty( $ret['message'] ) ) {
                                        $errors = ': ' . $ret['message'];
                                    }
                                }
                            } else {
                                if ( isset( $ret['message'] ) ) {
                                    $errors = $this->translate_error( $ret['message'] );
                                }
                            }
                            if ( isset( $ret['errors'] ) || isset( $ret['error'] ) ) {
                                if ( empty( $errors ) ) {
                                    $errors = $ret['message'];
                                }

                                $this->authorizationError( $errors, $ret['status'] );
                            }
                        }
                    }
                }
			}

            return $ret;

		}

		/**
		 * @param null $id
		 *
		 * @return array|mixed|object
		 * @throws Exception
		 */
		public function ping( $id = null ) {
            $res = null;
            if( ! empty( $this->token ) ) {
			    $organizationId = ( null !== $id ) ? $id : get_option( 'easypack_organization_id' );
			    $res            = $this->get( sprintf( '/organizations/%s', $organizationId ) );

                if ( $res && ! isset( $res['error'] ) ) {
                    $alerts = new Alerts();
                    $alerts->add_success( 'Inpost PL: ' . __( 'New API settings connection test passed.', 'woocommerce-inpost' ) );

                    update_option( 'easypack_api_login_error', '0' );
                } else {
                    update_option( 'easypack_api_login_error', '1' );
                }
            }

			return $res;
		}


		/**
		 * @param null $id
		 *
		 * @return array|mixed|object
		 * @throws Exception
		 */
		public function get_organization( $id = null ) {
            $res = null;
            if( ! empty( $this->token ) ) {
                $organizationId = ( null !== $id ) ? $id : get_option( 'easypack_organization_id' );
                $res = $this->get( sprintf('/organizations/%d', $organizationId ) );

                if ( isset( $res['error'] ) ) {
                    $status = isset( $res['status'] ) ? (int) $res['status'] : 401;
                    $this->authorizationError( $res['error'], $status );

                    return null;
                }
            }

			return $res;
		}

		/**
		 * @return array|mixed|object
		 * @throws Exception
		 */
		public function getServicesGlobal() {
			$res = $this->get( '/services/' );

			if ( isset( $res['error'] ) ) {
				$status = isset( $res['status'] ) ? (int) $res['status'] : 401;
				$this->authorizationError( $res['error'], $status );
                return null;
			}

			return $res;
		}

		/**
		 * @param string $message
		 * @param int $status
		 *
		 * @throws Exception
		 */
		private function authorizationError( $message, $status ) {
			$errors = $this->translate_error( $message );
            $errors = str_replace('<br>', ' ', $errors);

			$alerts = new Alerts();
			/*$alerts->add_error( 'Woocommerce Inpost: ' . ( is_string( $errors )
                    ? $errors
					: serialize( $errors ) . $message . ' ( ' . $status . ' )' ) );*/
            $alerts->add_error( 'InPost PL: ' . __( 'Error is occured during connection to API', 'woocommerce-inpost' ) );

		}


		/**
		 * @param $dispatch_point_id
		 *
		 * @return array|mixed|object
		 * @throws Exception
		 *
		 * @depecated
		 */
		public function dispatch_point( $dispatch_point_id ) {
			return $this->get( '/dispatch_points/' . $dispatch_point_id );
		}


		/**
		 * @param $args
		 *
		 * @return array|mixed|object
		 * @throws Exception
		 */
		public function dispatch_order( $args ) {
			$organisationId = get_option( 'easypack_organization_id' );
			$response = $this->post( sprintf( '/organizations/%d/dispatch_orders', $organisationId ), $args );

			return $response;
		}


		public function customer_parcel_create( $args ) {
			$organizationId = get_option( 'easypack_organization_id' );
			$response = $this->post( sprintf( '/organizations/%d/shipments', $organizationId ), $args );

			return $response;
		}


		public function customer_parcel_get_by_id( $id ) {

			$response = $this->get( sprintf( '/shipments/%d', $id ) );

			return $response;
		}

		/**
		 * @return string
		 * @throws Exception
		 */
		public function get_statuses() {
			$response = $this->get( '/statuses' );

			return $response;
		}

		/**
		 * @param $parcel_id
		 *
		 * @return array|mixed|object
		 * @throws Exception
		 */
		public function customer_parcel_cancel( $parcel_id ) {
			$response = $this->delete( '/shipments/' . $parcel_id );

			return $response;
		}

		public function customer_parcel_pay( $parcel_id ) {
            $args = array();
			$response = $this->post( '/parcels/' . $parcel_id . '/pay', $args );

			return $response;
		}


		public function customer_parcel_sticker( $parcel_id ) {
			return $this->get( '/shipments/' . $parcel_id . '/label', [ 'format' => 'Pdf' ] );
		}


		/**
		 * @param $shipment_ids
		 *
		 * @return array|mixed|object
		 * @throws Exception
		 */
		public function customer_shipments_labels( $shipment_ids ) {

            $result = [];
            if( ! empty( $shipment_ids ) ) {
                $organizationId = get_option('easypack_organization_id');
                $labelFormat = get_option('easypack_label_format');

                $args = [
                    'format' => 'pdf',
                    'shipment_ids' => $shipment_ids,
                    'type' => $labelFormat === 'A4' ? 'normal' : 'A6',
                ];

                $result =  $this->get(sprintf('/organizations/%d/shipments/labels', $organizationId), [], $args, true);
            }

            return $result;
		}

		/**
		 * @param $shipment_ids
		 *
		 * @return array|mixed|object
		 * @throws Exception
		 */
		public function customer_shipments_return_labels( $shipment_ids ) {
			$organizationId = get_option( 'easypack_organization_id' );
			$args = [
				'format'       => 'pdf',
				'shipment_ids' => $shipment_ids,
			];

			return $this->get( sprintf( '/organizations/%d/shipments/return_labels',
					$organizationId )
				, []
				, $args
				, true
			);
		}

		/**
		 * @param $dispatch_order_id
		 *
		 * @return array|mixed|object
		 * @throws Exception
		 */
		public function dispatch_order_pdf( $dispatch_order_id ) {
			$organizationId = get_option( 'easypack_organization_id' );
			$args = [
				'format' => 'Pdf',
			];

			return $this->get( sprintf( '/organizations/%d/dispatch_orders/%d/printout',
					$organizationId, $dispatch_order_id )
				, null
				, $args
				, true
			);
		}

		public function customer_parcel( $parcel_id ) {
			$response = $this->get( '/parcels/' . $parcel_id );
			$parcel   = $response;

			return $parcel;
		}


		/**
		 * @return mixed
		 * @deprecated
		 */
		public function api_country() {
			return $this->getCountry();
		}

		public function validate_phone( $phone ) {

			if ( $this->getCountry() == EasyPack_API::COUNTRY_UK ) {
				if ( preg_match( "/\A\d{10}\z/", $phone ) ) {
					return true;
				} else {
					return __( 'Invalid phone number. Valid phone number must contains 10 digits.', 'woocommerce-inpost' );
				}
			}
			if ( $this->getCountry() == EasyPack_API::COUNTRY_PL ) {
				if ( preg_match( "/\A[1-9]\d{8}\z/", $phone ) ) {
					return true;
				} else {
					return __( 'Invalid phone number. Valid phone number must contains 9 digits and must not begins with 0.',
                        'woocommerce-inpost' );
				}
			}

			return __( 'Invalid phone number.', 'woocommerce-inpost' );

		}

		/**
		 * @param $shipments
		 *
		 * @return array|mixed|object
		 * @throws Exception
		 */
		public function calculate_shipments( $shipments ) {
			$organizationId = get_option( 'easypack_organization_id' );
			$response = $this->post( sprintf( '/organizations/%d/shipments/calculate', $organizationId ), $shipments );

			return $response;
		}


		/**
		 * @return mixed
		 */
		public function getCountry() {
			return $this->country;
		}


		/**
		 * @param string $method
		 * @param string $url
		 * @param array $request
		 * @param array $response
		 */
		public function addToLog( $method, $url, $request, $response ) {
			$file = WOOCOMMERCE_INPOST_PLUGIN_DIR
			        . DIRECTORY_SEPARATOR
			        . 'log-inpost.txt';

			$line
				= sprintf( "******************\n\n%s\n%s\nURL:%s\nREQUEST:\n%s\nRESPONSE:\n%s\n",
				$method,
				date( "Y-m-d H:i:s", time() ),
				$url,
				preg_replace( '/[\x00-\x1F\x7F]/u', '', serialize( $request ) ),
				//remove non printable characters
				preg_replace( '/[\x00-\x1F\x7F]/u', '', serialize( $response ) )
			);

			file_put_contents( $file, $line, FILE_APPEND );
		}

	}


endif;

