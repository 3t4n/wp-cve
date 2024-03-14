<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Szamlazz_Admin_Notices', false ) ) :

	class WC_Szamlazz_Admin_Notices {

		//Notices
		private static $notices = array(
			'migrate' => array(
				'hide' => 'no',
			),
			'migrated' => array(
				'hide' => 'no',
			),
			'migrating' => array(
				'hide' => 'no',
			),
			'pro_expiration' => array(
				'hide' => 'no',
			),
			'curl_bug' => array(
				'hide' => 'no',
			),
		);

		//Init notices
		public static function init() {
			add_action( 'admin_init', array( __CLASS__, 'init_notices' ), 1 );
			add_action( 'admin_init', array( __CLASS__, 'hide_notice' ) );
			add_action( 'admin_head', array( __CLASS__, 'enqueue_notices' ) );
			add_action( 'wp_ajax_wc_szamlazz_hide_notice', array( __CLASS__, 'ajax_hide_notice' ) );
		}

		//Init notices array
		public static function init_notices() {
			$store_notices = get_user_meta( get_current_user_id(), 'wc_szamlazz_admin_notices_v2', true );
			self::$notices = wp_parse_args( empty( $store_notices ) ? array() : $store_notices, self::$notices );
		}

		//Add notices to admin_notices hook
		public static function enqueue_notices() {
			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				return;
			}

			foreach ( self::$notices as $key => $notice ) {

				if ( 'yes' === $notice['hide'] && ! isset( $notice['display_at'] ) && ! empty( $notice['interval'] ) ) {
					self::add_notice( $key, true );
				}

				if ( ! empty( $notice['display_at'] ) && time() > $notice['display_at'] ) {
					$notice['hide'] = 'no';
				}

				if ( 'no' === $notice['hide'] && $key != 'pro_expiration' ) {
					if(method_exists(__CLASS__, 'display_' . $key . '_notice')) {
						add_action( 'admin_notices', array( __CLASS__, 'display_' . $key . '_notice' ) );
					}
				}
			}

			//Always enque error notice
			add_action( 'admin_notices', array( __CLASS__, 'display_pro_expired_notice' ) );

		}

		//Add a notice to display/
		public static function add_notice( $notice, $delay = false ) {
			if ( ! empty( self::$notices[ $notice ] ) ) {
				if ( empty( $delay ) ) {
					self::$notices[ $notice ]['hide'] = 'no';
				} elseif ( ! empty( self::$notices[ $notice ]['interval'] ) ) {
					self::$notices[ $notice ]['hide'] = 'yes';
					self::$notices[ $notice ]['display_at'] = strtotime( self::$notices[ $notice ]['interval'] );
				}

				update_user_meta( get_current_user_id(), 'wc_szamlazz_admin_notices_v2', self::$notices );
			}
		}

		//Remove a notice
		public static function remove_notice( $notice ) {

			self::$notices[ $notice ]['hide'] = 'yes';
			self::$notices[ $notice ]['interval'] = '';
			self::$notices[ $notice ]['display_at'] = '';

			update_user_meta( get_current_user_id(), 'wc_szamlazz_admin_notices_v2', self::$notices );

			if($notice == 'pro_expiration') {
				update_option( '_wc_szamlazz_pro_expired_notice_hidden', true );
			}
		}

		//Hide a notice via ajax.
		public static function ajax_hide_notice() {
			check_ajax_referer( 'wc-szamlazz-hide-notice', 'security' );

			if ( isset( $_POST['notice'] ) ) {

				if ( ! current_user_can( 'manage_woocommerce' ) ) {
					wp_die( esc_html__( 'Cheatin&#8217; huh?', 'wc-szamlazz' ) );
				}

				$notice = sanitize_text_field( wp_unslash( $_POST['notice'] ) );

				if ( ! empty( $_POST['remind'] ) && 'yes' === $_POST['remind'] ) {
					self::add_notice( $notice, true );
				} else {
					self::remove_notice( $notice );
				}
			}

			wp_die();
		}

		//Hide welcome notice
		public static function hide_notice() {
			// Welcome notice.
			if ( ! empty( $_GET['welcome'] ) && ! empty( $_GET['page'] ) && $_GET['page'] == 'wc-settings' && ! empty( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'wc-szamlazz-hide-notice' ) ) {
				self::remove_notice( 'welcome' );
			}
		}

		//Invoice error message
		public static function display_migrate_notice() {
			$settings = get_option('woocommerce_wc_szamlazz_settings');
			if(current_user_can( 'manage_woocommerce' ) && !get_option('_wc_szamlazz_migrated') && $settings && isset($settings['wc_szamlazz_invoice_type']) && !get_option('_wc_szamlazz_migrating')) {
				include( dirname( __FILE__ ) . '/views/html-notice-migrate.php' );
			}
		}

		//Notice when data migration is done
		public static function display_migrated_notice() {
			if(get_option('_wc_szamlazz_migrated')) {
				include( dirname( __FILE__ ) . '/views/html-notice-migrated.php' );
			}
		}

		//Notice while migrating data
		public static function display_migrating_notice() {
			if(get_option('_wc_szamlazz_migrating')) {
				include( dirname( __FILE__ ) . '/views/html-notice-migrating.php' );
			}
		}

		//Invoice error message
		public static function display_pro_expired_notice() {
			if((!WC_Szamlazz_Pro::is_pro_enabled() && WC_Szamlazz_Pro::get_license_key()) && current_user_can( 'manage_woocommerce' )) {
				if(!get_option('_wc_szamlazz_pro_expired_notice_hidden')) {
					include( dirname( __FILE__ ) . '/views/html-notice-pro-expired.php' );
				}
			}
		}

		//Notice while migrating data
		public static function display_curl_bug_notice() {
			$version = curl_version();
			if(isset($version['version']) && $version['version'] == '7.79.0') {
				//include( dirname( __FILE__ ) . '/views/html-notice-curl-bug.php' );
			}
		}

	}

	WC_Szamlazz_Admin_Notices::init();

endif;
