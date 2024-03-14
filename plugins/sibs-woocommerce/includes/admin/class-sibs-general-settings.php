<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}


class Sibs_General_Settings {


	public static $tab_name = 'sibs_settings';

	public static $mandatory_field = array(
		'sibs_general_merchant_email',
		'sibs_general_merchant_no',
		'sibs_general_shop_url',
	);


	public static function init() {
		$page_request = Sibs_General_Functions::sibs_get_request_value( 'page' );
		$tab_request  = Sibs_General_Functions::sibs_get_request_value( 'tab' );
		if ( 'wc-settings' === $page_request && 'sibs_settings' === $tab_request ) { // safe request.
			self::sibs_save_tab_settings();
		}

		add_filter( 'woocommerce_settings_tabs_array', array( __CLASS__, 'sibs_add_settings_tab' ), 50 );
		add_action( 'woocommerce_settings_tabs_sibs_settings', array( __CLASS__, 'sibs_add_settings_page' ) );
		add_action( 'woocommerce_update_options_sibs_settings', array( __CLASS__, 'sibs_update_settings' ) );
	}


	public static function sibs_add_settings_tab( $woocommerce_tab ) {
		$woocommerce_tab[ self::$tab_name ] = 'SIBS ' . __( 'BACKEND_CH_GENERAL', 'wc-sibs' );
		return $woocommerce_tab;
	}


	public static function sibs_add_settings_page() {
		woocommerce_admin_fields( self::sibs_settings_fields() );
	}


	public static function sibs_update_settings() {
		woocommerce_update_options( self::sibs_settings_fields() );
	}


	public static function sibs_settings_fields() {
		global $sibs_payments;

		wp_enqueue_script( 'sibs_jira_script', 'https://payreto.atlassian.net/s/d41d8cd98f00b204e9800998ecf8427e-T/-adsv87/b/4/a44af77267a987a660377e5c46e0fb64/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?locale=en-US&collectorId=e3a422c7', array(), null );

		wp_enqueue_script( 'sibs_setting_script', plugins_url( 'assets/js/sibs-setting.js', realpath( __DIR__ . '/../' ) ), array(), null );

        $newUrl = get_option( 'home' ) . '/';

		//Display PHP Version
		$getPHPversion = phpversion();
		$PHPversion = '';
		$message = '';
		$plaintext = '';
		$nonce = '';
		$key = '';
		$ciphertext = '';
		$decrypted = '';
		
		$PHPSodiumAvailable = 0;
		$PHPSodiumCompactAvailable = 0;


		$key_from_configuration = "000102030405060708090a0b0c0d0e0f000102030405060708090a0b0c0d0e0f";
		$iv_from_http_header = "000000000000000000000000";
		$auth_tag_from_http_header = "CE573FB7A41AB78E743180DC83FF09BD";
		$http_body = "0A3471C72D9BE49A8520F79C66BBD9A12FF9";

		$encrypted = $http_body;
		$cipher = 'aes-256-gcm';
		$iv_len = 24;

		$iv = substr($encrypted, 0, $iv_len);
		$key = hex2bin($key_from_configuration);
 
		$iv = hex2bin($iv_from_http_header);
		$cipher_text = hex2bin($http_body . $auth_tag_from_http_header);
		
		if (function_exists('sodium_crypto_aead_aes256gcm_is_available')) {
			if (sodium_crypto_aead_aes256gcm_is_available()) {
				$PHPSodiumAvailable = 1;
				$PHPversion = $PHPversion . '<br> Sodium server available <br>';
			}
		}
		
		If ($PHPSodiumAvailable == 0) {
			$dir = substr(plugin_dir_path( __DIR__ ), 0 , -9). 'external'.DIRECTORY_SEPARATOR.'sodium_compat-1.7.0'.DIRECTORY_SEPARATOR.'autoload.php';			
			require_once $dir;

			if (function_exists('\Sodium\crypto_aead_aes256gcm_is_available')) {
				if (\Sodium\crypto_aead_aes256gcm_is_available()) {
					$PHPSodiumCompactAvailable = 1;
					$PHPversion = $PHPversion . '<br> Sodium compact available : '.$dir.' <br>';
				}
			}
		}

		$PHPversion = $PHPversion . '<br> Testing encryption decryption <br>';

		if($PHPSodiumAvailable == 1){
			$decrypted = sodium_crypto_aead_aes256gcm_decrypt( $cipher_text, "", $iv, $key);
		} else {
			if ($PHPSodiumCompactAvailable == 1) {
				$decrypted = \Sodium\crypto_aead_aes256gcm_decrypt($cipher_text, "", $iv, $key);				
			}
		}	

		$PHPversion = $PHPversion . "<br>" . $decrypted .'<br>';

		$settings = apply_filters(
			'woocommerce_' . self::$tab_name, array(
				array(
					'title' => 'SIBS ' . __( 'BACKEND_CH_GENERAL', 'wc-sibs' ),
					'id'    => 'sibs_general_settings',
					'desc'  => '',
					'type'  => 'title',
				),
				array(
					'desc' => $PHPversion,
					'type'  => 'title'
				),
				array(
					'title' => __( 'BACKEND_CH_LOGIN', 'wc-sibs' ),
					'id'    => 'sibs_general_login',
					'css'   => 'width:25em;',
					'type'  => 'text',
				),
				array(
					'title' => __( 'BACKEND_CH_PASSWORD', 'wc-sibs' ),
					'id'    => 'sibs_general_password',
					'css'   => 'width:25em;',
					'type'  => 'text',
				),
				array(
					'title' => __( 'BACKEND_GENERAL_MERCHANTEMAIL', 'wc-sibs' ) . ' * ',
					'id'    => 'sibs_general_merchant_email',
					'css'   => 'width:25em;',
					'type'  => 'text',
				),
				array(
					'title' => __( 'SIBS_BACKEND_GENERAL_MERCHANTNO', 'wc-sibs' ) . ' * ',
					'id'    => 'sibs_general_merchant_no',
					'css'   => 'width:25em;',
					'type'  => 'text',
					'desc'  => '<br />' . __( 'BACKEND_TT_MERCHANT_ID', 'wc-sibs' ),
				),
				array(
					'title' => __( 'BACKEND_GENERAL_SHOPURL', 'wc-sibs' ) . ' * ',
					'id'    => 'sibs_general_shop_url',
					'css'   => 'width:25em;',
					'type'  => 'text',
				),
				array(
					'title'   => __( 'BACKEND_CH_DOB_GENDER', 'wc-sibs' ),
					'id'      => 'sibs_general_dob_gender',
					'css'     => 'width:25em;',
					'type'    => 'select',
					'desc'    => '<br />' . __( 'BACKEND_TT_DOB_GENDER', 'wc-sibs' ),
					'options' => array(
						'0' => __( 'BACKEND_BT_NO', 'wc-sibs' ),
						'1' => __( 'BACKEND_BT_YES', 'wc-sibs' ),
					),
					'default' => '0',
				),
                array(
                    'title'   => __( 'BACKEND_CH_CUSTOM_STATUS', 'wc-sibs' ),
                    'id'      => 'sibs_general_custom_status',
                    'css'     => 'width:25em;',
                    'type'    => 'select',
                    'desc'    => '<br />' . __( 'BACKEND_TT_CUSTOM_STATUS', 'wc-sibs' ),
                    'options' => array(
                        '0' => __( 'BACKEND_BT_NO', 'wc-sibs' ),
                        '1' => __( 'BACKEND_BT_YES', 'wc-sibs' ),
                    ),
                    'default' => '0',
                ),
				array(
					'title'   => __( 'SIBS_BACKEND_GENERAL_ENVIRONMENT', 'wc-sibs' ),
					'id'      => 'sibs_general_environment',
					'css'     => 'width:25em;',
					'type'    => 'select',
					'options' => array(
						'CER' 	=> __( 'CER', 'wc-sibs' ),
						'DEV' 	=> __( 'DEV', 'wc-sibs' ),
						'LIVE' 	=> __( 'LIVE', 'wc-sibs' ),
						'QLY' 	=> __( 'QLY', 'wc-sibs' ),
						
					),
					'default' => 'QLY',
				),
				array(
					'title'   => __( 'SIBS WebHook Secret', 'wc-sibs' ),
					'type'    => 'text',
					'default' => '',
					'id'   => 'sibs_webhook_secret',
					'desc'    => '<br />' . sprintf( __( 'Place here your SIBS\' WebHook Secret. <br> Provide this WebHook URL %s to your SIBS point of contact.', 'wc-sibs' ), '<a href="#" id="webhookurl" class="btn btn-primary btn-large">' . $newUrl . 'wp-json/sibs-api/v1/callback_hook' . '</a>'),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'sibs_vendor_script',
				),
				array(
					'title' => __( 'SIBS_BACKEND_RAISING_ISSUE', 'wc-sibs' ),
					'id'    => 'sibs_general_raise_issue',
					'desc'  => '<a href="#" id="feedback-button" class="btn btn-primary btn-large">' . __( 'BACKEND_GENERAL_REPORT', 'wc-sibs' ) . '</a>',
					'type'  => 'title',
				)
			)
		);
		return apply_filters( 'woocommerce_' . self::$tab_name, $settings );
	}


	public static function sibs_save_tab_settings() {
		$save_request = Sibs_General_Functions::sibs_get_request_value( 'save' );
		if ( $save_request ) {
			$is_fill_mandatory_fields = self::sibs_is_fill_mandatory_fields( $_REQUEST ); // input var okay.

			if ( ! $is_fill_mandatory_fields ) {
				$get           = isset( $_GET ) ? $_GET : null; // input var okay.
				$redirect      = get_admin_url() . 'admin.php?' . http_build_query( $get );
				$redirect      = remove_query_arg( 'save' );
				$error_message = __( 'ERROR_GENERAL_MANDATORY', 'wc-sibs' );
				$redirect      = add_query_arg( 'wc_error', rawurlencode( esc_attr( $error_message ) ), $redirect );
				wp_safe_redirect( $redirect );
				exit();
			}
		}
	}


	public static function sibs_is_fill_mandatory_fields( $request ) {

		foreach ( $request as $fields_name => $field_value ) {
			if ( in_array( $fields_name, self::$mandatory_field, true ) ) {
				if ( trim( $field_value ) === '' ) {
					return false;
				}
			}
		}

		return true;
	}
}

Sibs_General_Settings::init();
