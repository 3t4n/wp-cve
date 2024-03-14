<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Szamlazz_Pro', false ) ) :

	class WC_Szamlazz_Pro {
		public static $activation_url;
		public static $name;
		public static $id;

		public static function init() {

			//Define plugin specific stuff
			self::$activation_url = 'https://visztpeter.me/wp-json/vp_woo_license/';
			self::$name = 'wc-szamlazz';
			self::$id = str_replace('-', '_', self::$name);

			//Check and save PRO version
			add_action( 'wp_ajax_'.self::$id.'_license_activate', array( __CLASS__, 'pro_activate' ) );
			add_action( 'wp_ajax_'.self::$id.'_license_deactivate', array( __CLASS__, 'pro_deactivate' ) );
			add_action( 'wp_ajax_'.self::$id.'_license_validate', array( __CLASS__, 'pro_validate' ) );

			//Scheduled action to check license activation
			add_action( self::$id.'_pro_key_check', array( __CLASS__, 'pro_validate' ) );

		}

		public static function is_pro_enabled() {
			return get_option('_'.self::$id.'_pro_enabled', false);
		}

		public static function get_license_key() {
			return get_option('_'.self::$id.'_pro_key', '');
		}

		public static function get_license_key_meta() {
			return get_option('_'.self::$id.'_pro_meta', array());
		}

		public static function pro_activate($pro_key = false) {
			check_ajax_referer( 'wc-szamlazz-license-check', 'nonce' );
			if ( !current_user_can( 'manage_woocommerce' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this action.' ) );
			}

			//Get submitted key
			if(!$pro_key) {
				$pro_key = sanitize_text_field($_POST['key']);
			}

			//Execute request
			$response = wp_remote_get( self::$activation_url.'activate/'.$pro_key.'/'.self::$name );

			//Check for errors
			if( is_wp_error( $response ) ) {
				wp_send_json_error(array(
					'message' => __('Unable to activate the PRO version. Please make sure that the entered data is correct.', 'wc-szamlazz')
				));
			}

			//Get body
			$body = wp_remote_retrieve_body( $response );
			$response_code = wp_remote_retrieve_response_code( $response );

			//Try to convert into json
			$json = json_decode( $body, true );

			//If not 200, its an error
			if($response_code != 200 || isset($json['fail'])) {
				wp_send_json_error(array(
					'message' => __('Unable to activate the PRO version. Please make sure that the entered data is correct.', 'wc-szamlazz')
				));
			} else {
				update_option('_'.self::$id.'_pro_key', $pro_key);
				update_option('_'.self::$id.'_pro_enabled', true);
				update_option('_'.self::$id.'_pro_meta', $json);

				//Schedule an action to check key periodically to see if its still valid
				WC()->queue()->schedule_recurring( time()+WEEK_IN_SECONDS, WEEK_IN_SECONDS, self::$id.'_pro_key_check', array(), self::$id );

				//Return success
				wp_send_json_success();
			}

		}

		public static function pro_deactivate() {
			check_ajax_referer( 'wc-szamlazz-license-check', 'nonce' );
			if ( !current_user_can( 'manage_woocommerce' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this action.' ) );
			}

			//Get submitted key
			$pro_key = self::get_license_key();

			//Execute request
			$response = wp_remote_get( self::$activation_url.'deactivate/'.$pro_key );

			//Check for errors
			if( is_wp_error( $response ) ) {
				wp_send_json_error(array(
					'message' => __('Unable to deactivate the PRO version. Please make sure that the entered data is correct.', 'wc-szamlazz')
				));
			}

			//Delete from options
			delete_option('_'.self::$id.'_pro_key');
			delete_option('_'.self::$id.'_pro_meta');
			delete_option('_'.self::$id.'_pro_enabled');

			//Stop key checks
			WC()->queue()->cancel_all( self::$id.'_pro_key_check' );

			wp_send_json_success();
		}

		public static function pro_validate() {
			check_ajax_referer( 'wc-szamlazz-license-check', 'nonce' );
			if ( !current_user_can( 'manage_woocommerce' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this action.' ) );
			}

			//Get submitted key
			$pro_key = self::get_license_key();
			if(!$pro_key) return false;

			//Execute request
			$response = wp_remote_get( self::$activation_url.'validate/'.$pro_key );

			//Check for errors
			if( is_wp_error( $response ) ) return false;

			//Get body
			$body = wp_remote_retrieve_body( $response );
			$response_code = wp_remote_retrieve_response_code( $response );

			//Try to convert into json
			$json = json_decode( $body, true );

			//If not 200, its an error
			if($response_code != 200) return false;

			//Else, check for error
			if(isset($json['fail'])) {
				delete_option('_'.self::$id.'_pro_enabled');
			} else {
				update_option('_'.self::$id.'_pro_enabled', true);
			}

			//Update meta
			update_option('_'.self::$id.'_pro_meta', $json);

			return true;
		}

		public static function migrate_old_pro() {
			if(self::is_pro_enabled()) {

				//This will download the new license key meta if theres any
				self::pro_validate();

			}
		}

	}

	WC_Szamlazz_Pro::init();

endif;
