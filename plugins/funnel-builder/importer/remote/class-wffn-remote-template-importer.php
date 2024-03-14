<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class WFFN_Remote_Template_Importer
 * @package WFFN
 * @author XlPlugins
 */
if ( ! class_exists( 'WFFN_Remote_Template_Importer' ) ) {
	class WFFN_Remote_Template_Importer {

		private static $instance = null;

		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public static function get_error_message( $code ) {
			$messages = [
				'license-or-domain-invalid' => __( 'This site does not have access to template library.  To get access activate the license. For any further help contact support.', 'funnel-builder' ),
				'license-expired'           => __( 'This site does not have access to template library as license has expired. To get access renew the license. For any further help contact support.', 'funnel-builder' ),
				'invalid-step'              => sprintf( __( 'Please check if you have valid license key. Try activating the license again. For any further help contact support. <a href=%s target="_blank">Go to Licenses</a>', 'funnel-builder' ), esc_url( admin_url( 'admin.php?page=bwf&path=/settings' ) ) ),
				'template-not-exists'       => __( 'Template not available in cloud library. Please contact support.', 'funnel-builder' )
			];
			if ( isset( $messages[ $code ] ) ) {
				return $messages[ $code ];
			}

			return $code;
		}

		/**
		 * Import template remotely.
		 *
		 * @param $step
		 * @param $template_slug
		 * @param $builder
		 * @param $steps
		 *
		 * @return array|false|mixed|string
		 */
		public function get_remote_template( $step, $template_slug, $builder, $steps = [] ) {

			if ( empty( $step ) || empty( $template_slug ) || empty( $builder ) ) {
				return '';
			}

			$license = $this->get_license_key();

			$requestBody = array(
				"step"     => $step,
				"domain"   => $this->get_domain(),
				"license"  => $license,
				"template" => $template_slug,
				"builder"  => $builder
			);

			if ( 'funnel' === $step && count( $steps ) > 0 ) {
				$requestBody['steps'] = $steps;
			}

			$requestBody = wp_json_encode( $requestBody );

			$endpoint_url = $this->get_template_api_url();
			$response     = wp_remote_post( $endpoint_url, array(
				"body"    => $requestBody,
				"timeout" => 30, //phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
				'headers' => array(
					'content-type' => 'application/json'
				)
			) );

			BWF_Logger::get_instance()->log( 'Import $requestBody: ' . print_r( $requestBody, true ), 'wffn_template_import' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			if ( $response instanceof WP_Error ) {
				if ( is_object( $response ) && $response->errors ) {
					if ( is_array( $response->errors ) && $response->errors['http_request_failed'] ) {
						return [ 'error' => isset( $response->errors['http_request_failed'][0] ) ? $response->errors['http_request_failed'] : __( 'HTTP Request Failed', 'funnel-builder' ) ];
					}
				}

				return false;
			}

			$response = json_decode( $response['body'], true );
			if ( ! is_array( $response ) ) {
				return [ 'error' => __( 'It seems we are unable to import this template from the cloud library. Please contact support.', 'funnel-builder' ) ];
			}

			if ( isset( $response['error'] ) ) {
				return [ 'error' => self::get_error_message( $response['error'] ) ];
			}

			if ( 'funnel' !== $step && ! isset( $response[ $step ] ) ) {
				return [ 'error' => __( 'No Template found', 'funnel-builder' ) ];
			}

			if ( 'funnel' === $step ) {
				$funnels = [];
				foreach ( $steps as $type => $template ) {

					if ( isset( $response[ $type ] ) ) {
						$data = array(
							'type'          => $type,
							'title'         => $this->get_step_title( $type ),
							'template'      => $template,
							'template_type' => $builder,
						);

						if ( 'wc_upsells' === $type ) {
							$data['status']          = true;
							$data['meta']['steps'][] = array(
								'type'          => 'upsell',
								'title'         => __( 'Offer', 'funnel-builder' ),
								'template'      => $template,
								'template_type' => $builder,
								'state'         => true,
								'meta'          => array(
									'_wfocu_setting' => array(
										'products'       => [],
										'fields'         => [],
										'template'       => '',
										'template_group' => '',
										'settings'       => [],
									)
								)
							);
						}

						$funnels['steps'][] = $data;

						$directory = $builder . '/' . $type . '/' . $template;

						if ( ! is_dir( WFFN_TEMPLATE_UPLOAD_DIR ) ) {
							mkdir( WFFN_TEMPLATE_UPLOAD_DIR );  //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.directory_mkdir
						}

						if ( ! is_dir( WFFN_TEMPLATE_UPLOAD_DIR . '/' . $builder ) ) {
							mkdir( WFFN_TEMPLATE_UPLOAD_DIR . '/' . $builder ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.directory_mkdir
						}


						if ( ! is_dir( WFFN_TEMPLATE_UPLOAD_DIR . '/' . $builder . '/' . $type ) ) {
							mkdir( WFFN_TEMPLATE_UPLOAD_DIR . '/' . $builder . '/' . $type ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.directory_mkdir
						}
						$template_path = WFFN_TEMPLATE_UPLOAD_DIR . $directory . '.json';
						file_put_contents( $template_path, $response[ $type ] ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents

					}

				}
				if ( 0 === count( $funnels ) ) {
					return [ 'error' => __( 'No Template found', 'funnel-builder' ) ];
				}

				return array( $funnels );
			}


			return $response[ $step ];
		}

		public function get_domain() {
			global $sitepress;
			$domain = site_url();

			if ( isset( $sitepress ) && ! is_null( $sitepress ) ) {
				$default_language = $sitepress->get_default_language();
				$domain           = $sitepress->convert_url( $sitepress->get_wp_api()->get_home_url(), $default_language );
			}

			/**
			 * Get woofunnels plugins data from the options
			 * consider multisite setups
			 */
			if ( is_multisite() ) {
				/**
				 * Check if sitewide installed, if yes then get the plugin info from primary site
				 */
				$active_plugins = get_site_option( 'active_sitewide_plugins', array() );

				if ( is_array( $active_plugins ) && in_array( WFFN_PLUGIN_BASENAME, apply_filters( 'active_plugins', $active_plugins ), true ) || array_key_exists( WFFN_PLUGIN_BASENAME, apply_filters( 'active_plugins', $active_plugins ) ) ) {
					$domain = get_site_url( get_network()->site_id );
				}

			}
			$domain = str_replace( [ 'https://', 'http://' ], '', $domain );
			$domain = trim( $domain, '/' );

			return $domain;
		}

		/**
		 * Get license key.
		 * @return mixed
		 */
		public function get_license_key( $additional_info = false ) {
			/**
			 * Get woofunnels plugins data from the options
			 * consider multisite setups
			 */
			if ( is_multisite() ) {
				/**
				 * Check if sitewide installed, if yes then get the plugin info from primary site
				 */
				$active_plugins = get_site_option( 'active_sitewide_plugins', array() );

				if ( is_array( $active_plugins ) && defined( 'WFFN_PRO_PLUGIN_BASENAME' ) && ( in_array( WFFN_PRO_PLUGIN_BASENAME, apply_filters( 'active_plugins', $active_plugins ), true ) || array_key_exists( WFFN_PRO_PLUGIN_BASENAME, apply_filters( 'active_plugins', $active_plugins ) ) ) ) {
					$woofunnels_data = get_blog_option( get_network()->site_id, 'woofunnels_plugins_info', [] );
				} else {
					$woofunnels_data = get_option( 'woofunnels_plugins_info', [] );
				}
			} else {
				$woofunnels_data = get_option( 'woofunnels_plugins_info' );
			}

			if ( ! is_array( $woofunnels_data ) || 0 === count( $woofunnels_data ) || ! defined( 'WFFN_PRO_PLUGIN_BASENAME' ) ) {
				return false;
			}

			$plugin_hash = sha1( WFFN_PRO_PLUGIN_BASENAME );

			/** Not present */
			if ( ! isset( $woofunnels_data[ $plugin_hash ] ) ) {
				return false;
			}

			if ( true === $additional_info ) {
				return $woofunnels_data[ $plugin_hash ];
			}

			return $woofunnels_data[ $plugin_hash ]['data_extra']['api_key'];
		}

		public function get_template_api_url() {
			return 'https://gettemplates.funnelkit.com/';
		}

		public function get_step_title( $type ) {
			$args = [
				'landing'     => __( 'Landing Page', 'funnel-builder' ),
				'wc_checkout' => __( 'Checkout', 'funnel-builder' ),
				'wc_upsells'  => __( 'Upsells', 'funnel-builder' ),
				'wc_thankyou' => __( 'Thank you Page', 'funnel-builder' ),
				'optin'       => __( 'Optin', 'funnel-builder' ),
				'optin_ty'    => __( 'Optin Confirmation Page', 'funnel-builder' ),
			];
			if ( isset( $args[ $type ] ) ) {
				return $args[ $type ];
			}
		}

	}

	WFFN_Core::register( 'remote_importer', 'WFFN_Remote_Template_Importer' );
}