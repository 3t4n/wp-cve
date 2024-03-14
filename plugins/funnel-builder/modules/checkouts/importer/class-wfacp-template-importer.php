<?php

#[AllowDynamicProperties]

  class WFACP_Template_Importer {

	private static $instance = null;
	private static $importer = [];

	private function __construct() {
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

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function register( $builder, $importer ) {
		if ( ! isset( self::$importer[ $builder ] ) ) {
			self::$importer[ $builder ] = $importer;
		}
	}

	/**
	 * @param $aero_id
	 * @param $builder
	 * @param $slug
	 *
	 * @return bool
	 */
	public function import( $aero_id, $builder, $slug, $is_multi = 'no' ) {
		if ( isset( self::$importer[ $builder ] ) ) {
			$importer = self::$importer[ $builder ];
			if ( method_exists( $importer, 'import_child' ) ) {

				$status = $importer->import_child( $aero_id, $slug, $is_multi );
			} else {

				$status = $importer->import( $aero_id, $slug, $is_multi );
			}

			return $status;
		}

		if ( $builder == 'elementor' ) {
			if ( ( ! version_compare( get_bloginfo( 'version' ), '5.0', '>=' ) && ( version_compare( ELEMENTOR_VERSION, '2.8.0', '>=' ) ) ) ) {
				$message = sprintf( esc_html__( 'Elementor requires WordPress version %s+. please update the wordpress version to import the template.', 'woofunnels-aero-checkout' ), '5.0' );

				return [ 'error' => $message ];
			}
		}
		if ( $builder == 'divi' ) {
			$response = WFACP_Common::check_builder_status( 'divi' );
			if ( ! empty( $response['error'] ) ) {
				$message = $response['error'];
			}

			return [ 'error' => $message ];

		}


		return false;
	}

	/**
	 * @param $aero_id
	 * @param $builder
	 * @param $slug
	 *
	 * @return array||null
	 */
	public function export( $aero_id, $builder, $slug ) {
		if ( isset( self::$importer[ $builder ] ) && self::$importer[ $builder ] instanceof WFACP_Import_Export ) {
			$importer    = self::$importer[ $builder ];
			$export_data = $importer->export( $aero_id, $builder, $slug );

			return $export_data;
		}

		return null;
	}

	public function get_importer( $builder ) {
		if ( isset( self::$importer[ $builder ] ) && self::$importer[ $builder ] instanceof WFACP_Import_Export ) {
			return self::$importer[ $builder ];
		}

		return null;
	}

	/**
	 * Import template remotely.
	 * @return mixed
	 */
	public function get_remote_template( $template_id, $builder ) {
		if ( empty( $template_id ) || empty( $builder ) ) {
			return '';
		}
		$funnel_step = 'wc_checkout';

		$template_file_path = $builder . '/' . $funnel_step . '/' . $template_id;
		$defined_wffn       = defined( 'WFFN_TEMPLATE_UPLOAD_DIR' );
		$file_exist         = ( $defined_wffn ) ? file_exists( WFFN_TEMPLATE_UPLOAD_DIR . $template_file_path . '.json' ) : false;

		if ( $defined_wffn && $file_exist ) {
			$content = file_get_contents( WFFN_TEMPLATE_UPLOAD_DIR . $template_file_path . '.json' );
			unlink( WFFN_TEMPLATE_UPLOAD_DIR . $template_file_path . '.json' );

			return [ 'success' => true, 'data' => $content ];
		}

		$license = false;

		$requestBody = array(
			'step'     => $funnel_step,
			"domain"   => $this->get_domain(),
			"license"  => $license,
			"template" => $template_id,
			"builder"  => $builder
		);

		$requestBody  = wp_json_encode( $requestBody );
		$endpoint_url = $this->get_template_api_url();

		$response = wp_remote_post( $endpoint_url, array(
			"body"    => $requestBody,
			"timeout" => 30,
			'headers' => [
				'content-type' => 'application/json'
			]
		) );
		BWF_Logger::get_instance()->log( 'Import $requestBody: ' . print_r( $requestBody, true ), 'wffn_template_import' );
		if ( $response instanceof WP_Error ) {
			return false;
		}

		$response = json_decode( $response['body'], true );
		if ( ! is_array( $response ) ) {
			return [ 'error' => __( 'It seems we are unable to import this template from the cloud library. Please contact support.', 'funnel-builder' ) ];
		}
		if ( isset( $response['error'] ) ) {
			return [ 'error' => self::get_error_message( $response['error'] ) ];
		}
		$checkout_data = $response[ $funnel_step ];

		if ( empty( $checkout_data ) ) {
			return [ 'error' => __( 'No Template found', 'funnel-builder') ];
		}

		return [ 'success' => true, 'data' => $checkout_data ];
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


	public function get_template_api_url() {
		return 'https://gettemplates.funnelkit.com/';
	}

	public static function update_import_page_settings( $aero_id, $import_page_settings ) {
		if ( ! empty( $import_page_settings ) ) {
			$page_settings = WFACP_Common::get_page_settings( $aero_id );
			foreach ( $import_page_settings as $key => $setting ) {
				$page_settings[ $key ] = $import_page_settings[ $key ];
			}
			update_post_meta( $aero_id, '_wfacp_page_settings', $page_settings );
		}
	}
}

WFACP_Core::register( 'importer', WFACP_Template_Importer::get_instance() );
