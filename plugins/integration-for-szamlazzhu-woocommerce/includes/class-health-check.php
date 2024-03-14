<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Szamlazz_Health_Check', false ) ) :

	class WC_Szamlazz_Health_Check {

		//Init notices
		public static function init() {

			add_filter( 'debug_information', array( __CLASS__, 'debug_info' ) );
			add_filter( 'site_status_tests', array(__CLASS__, 'status_tests') );
			add_action( 'wp_ajax_health-check-wc-szamlazz_teszt', array(__CLASS__, 'status_tests_ajax') );
			add_action( 'woocommerce_system_status_report', array( __CLASS__, 'add_status_page_box' ) );

		}

		public static function debug_info($debug) {
			$szamlazz = array(
				'wc-szamlazz' => array(
					'label'			 => __( 'Számlázz.hu', 'wc-szamlazz' ),
					'description' => sprintf(
						__(
							'Diagnostic informations related to the Számlázz.hu WooCommerce extension. If you have some questions or something is not working correctly, please forward these details too: <a href="%1$s" target="_blank" rel="noopener noreferrer">Támogatás</a>',
							'wc-szamlazz'
						),
						esc_html( 'https://visztpeter.me/' )
					),
					'fields'			=> self::debug_info_data(),
				),
			);
			$debug = array_merge($debug, $szamlazz);
			return $debug;
		}

		public static function status_tests($core_tests) {

			$core_tests['direct']['wc_szamlazz_curl'] = array(
				'label' => __( 'Számlázz.hu requirements', 'wc-szamlazz' ),
				'test'	=> function() {
					$settings = get_option( 'woocommerce_wc_szamlazz_settings', null );

					$result = array(
						'label'			 => 'Számlázz.hu requirements',
						'status'			=> 'good',
						'badge'			 => array(
							'label' => __( 'Számlázz.hu' ),
							'color' => 'blue',
						),
						'description' => __('The website and hosting meet all the requirements for a successful invoice generation with the Számlázz.hu WooCommerce extension.', 'wc-szamlazz'),
						'test'				=> 'wc_szamlazz_php_version',
					);

					//Check for cURL
					if(!function_exists('curl_version')) {
						$result['status'] = 'critical';
						$result['badge']['color'] = 'red';
						$result['description'] = __('The <strong>WooCommerce + Szamlazz.hu</strong> extension is using the cURL function. Looks like this feature is disabled on your website. Please contact your hosting provider for more info.', 'wc-szamlazz');
					}

					//Username/password
					if((isset($settings['agent_key']) && $settings['agent_key'] != '') || defined( 'WC_SZAMLAZZ_AGENT_KULCS' )) {
						//all good
					} else {
						$result['status'] = 'critical';
						$result['badge']['color'] = 'red';
						$result['description'] = __('You need to enter the Számla Agent Key in the settings to use the <strong>WooCommerce + Szamlazz.hu</strong> extension.', 'wc-szamlazz');
						$result['actions'] = sprintf(
							'<p><a href="%s" target="_blank" rel="noopener noreferrer">%s <span aria-hidden="true" class="dashicons dashicons-admin-generic"></span></a></p>',
							esc_url( admin_url( 'admin.php?page=wc-settings&tab=integration&section=wc_szamlazz' ) ),
							__( 'Settings', 'wc-szamlazz' )
						);
					}

					return $result;
				}
			);

			//Debug mode is turned on
			$core_tests['direct']['wc_szamlazz_debug'] = array(
				'label' => __( 'Számlázz.hu debug mode', 'wc-szamlazz' ),
				'test'	=> function() {
					$settings = get_option( 'woocommerce_wc_szamlazz_settings', null );

					$result = array(
						'label'			 => __('Számlázz.hu debug mode is turned off', 'wc-szamlazz'),
						'status'			=> 'good',
						'badge'			 => array(
							'label' => __( 'Számlázz.hu' ),
							'color' => 'blue',
						),
						'description' => __('Számlázz.hu WooCOmmerce debug mode is turned off.', 'wc-szamlazz'),
						'test'				=> 'wc_szamlazz_check_debug_mode',
					);

					//If debug mode is turned on
					if($settings['debug'] && $settings['debug'] == 'yes') {
						$result['label'] = __('Számlázz.hu debug mode is turned on', 'wc-szamlazz');
						$result['status'] = 'critical';
						$result['badge']['color'] = 'red';
						$result['description'] = __("The <strong>WooCommerce + Szamlazz.hu</strong> extension's debug mode is turned on. Make sure you turned this off if you are using it in a live environment.", 'wc-szamlazz');
						$result['actions'] = sprintf(
							'<p><a href="%s" target="_blank" rel="noopener noreferrer">%s <span aria-hidden="true" class="dashicons dashicons-admin-generic"></span></a></p>',
							esc_url( admin_url( 'admin.php?page=wc-settings&tab=integration&section=wc_szamlazz' ) ),
							__( 'Settings', 'wc-szamlazz' )
						);
					}

					return $result;
				}
			);

			$core_tests['async']['wc_szamlazz_generate'] = array(
				'label' => __( 'Számlázz.hu invoice generation', 'wc-szamlazz' ),
				'test'	=> 'wc_szamlazz_teszt',
			);

			return $core_tests;
		}

		public static function status_tests_ajax() {
			check_ajax_referer( 'health-check-site-status' );
			if ( !current_user_can( 'edit_shop_orders' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this action.', 'wc-szamlazz' ) );
			}
			
			//Build Xml
			$szamla = new WCSzamlazzSimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><xmlszamlakifiz xmlns="http://www.szamlazz.hu/xmlszamlakifiz" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.szamlazz.hu/xmlszamlakifiz http://www.szamlazz.hu/docs/xsds/agentkifiz/xmlszamlakifiz.xsd"></xmlszamlakifiz>');
			$szamla->appendXML(WC_Szamlazz()->get_authentication_xml_object(false));
			$szamla->beallitasok->addChild('szamlaszam', '1');
			$szamla->beallitasok->addChild('additiv', 'false');
			$kifizetes = $szamla->addChild('kifizetes');
			$kifizetes->addChild('datum', date('Y-m-d') );
			$kifizetes->addChild('jogcim', '' );
			$kifizetes->addChild('osszeg', 1);
			$xml = $szamla->asXML();

			//Get response from Számlázz.hu
			$xml_response = WC_Szamlazz()->xml_generator->generate($xml, rand(), 'action-szamla_agent_kifiz');

			$result = array(
				'label'			 => __( 'Számlázz.hu is connected.', 'wc-szamlazz' ),
				'status'			=> 'good',
				'badge'			 => array(
					'label' => __( 'Számlázz.hu' ),
					'color' => 'blue',
				),
				'description' => sprintf(
					'<p>%s</p>',
					__( "The Számlázz.hu WooCommerce extension was able to communicate with Számlázz.hu with the Agent Key. Looks like everything is working correctly.", 'wc-szamlazz' )
				),
				'actions'		 => '',
				'test'				=> 'wc_szamlazz_test_generate_invoice',
			);

			if($xml_response['error']) {
				if($xml_response['http_error']) {
					$result['label'] = __('Számlázz.hu is not connected', 'wc-szamlazz');
					$result['status'] = 'critical';
					$result['badge']['color'] = 'red';
					$result['description'] = sprintf(
						'<p>%s</p><p>%s</p>',
						__( "The Számlázz.hu WooCommerce extension was unable to connect with Számlázz.hu. cURL might be disabled on your server, or maybe the request was blocked by your hosting provider's firewall. This is the original error message:", 'wc-szamlazz' ),
						esc_html($xml_response['http_error'])
					);
				} else {
					if($xml_response['agent_error_code'] == 3) {
						$result['label'] = __('Számlázz.hu is not connected', 'wc-szamlazz');
						$result['status'] = 'critical';
						$result['badge']['color'] = 'red';
						$result['description'] = sprintf(
							'<p>%s</p><p>%s</p>',
							__( "The Számlázz.hu WooCommerce extension was unable to connect with Számlázz.hu. The issue is most likely an invalid Agent Key. This is the original error message:", 'wc-szamlazz' ),
							esc_html($xml_response['agent_error'])
						);
					} else if($xml_response['agent_error_code'] == 136) {
						$result['label'] = __('Számlázz.hu is not connected', 'wc-szamlazz');
						$result['status'] = 'critical';
						$result['badge']['color'] = 'red';
						$result['description'] = sprintf(
							'<p>%s</p><p>%s</p>',
							__( "The Számlázz.hu WooCommerce extension was unable to connect with Számlázz.hu, because you don't have permission to use the Agent service. Make sure you have a valid subscription. This is the original error message:", 'wc-szamlazz' ),
							esc_html($xml_response['agent_error'])
						);
					}
				}
			}

			wp_send_json_success($result);
		}

		public static function debug_info_data() {
			$debug_info = array();

			//PRO verzió
			$debug_info['wc_szamlazz_pro_version'] = array(
				'label'	 => __('PRO version', 'wc-szamlazz'),
				'value'	 => WC_Szamlazz_Pro::is_pro_enabled()
			);

			//Invoice path
			$UploadDir = wp_upload_dir();
			$UploadURL = $UploadDir['basedir'];
			$location	= realpath($UploadURL . "/wc_szamlazz/");
			$debug_info['wc_szamlazz_path'] = array(
				'label'	 => __('Path', 'wc-szamlazz'),
				'value'	 => $location
			);

			//IPN URL
			$settings_api = new WC_Szamlazz_Settings();
			$debug_info['wc_szamlazz_ipn'] = array(
				'label'	 => __('IPN URL', 'wc-szamlazz'),
				'value'	 => $settings_api->get_ipn_url(),
				'private' => true
			);

			//Payment options
			$payment_options = get_option('wc_szamlazz_payment_method_options_v2');
			$debug_info['wc_szamlazz_payment_options'] = array(
				'label'	 => __('Payment methods', 'wc-szamlazz'),
				'value'	 => print_r($payment_options, true)
			);

			//Display saved settings
			$settings = get_option( 'woocommerce_wc_szamlazz_settings', null );
			$options = $settings_api->form_fields;
			unset($options['agent_key']);

			foreach ($options as $option_id => $option) {
				if(!in_array($option['type'], array('pro', 'title', 'payment_methods', 'accounting_details', 'ipn'))) {
					if(isset($settings[$option_id]) && isset($option['title'])) {
						$debug_info[$option_id] = array(
							'label'	 => $option['title'],
							'value'	 => $settings[$option_id]
						);
					}
				}
			}

			return $debug_info;
		}

		public static function add_status_page_box() {
			$debug_info = self::debug_info_data();
			include( dirname( __FILE__ ) . '/views/html-status-report.php' );
		}

	}

	WC_Szamlazz_Health_Check::init();

endif;
