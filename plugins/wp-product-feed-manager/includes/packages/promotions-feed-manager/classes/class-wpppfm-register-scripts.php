<?php

/**
 * WPPPFM Google Merchant Promotions Feed Register Scripts.
 *
 * @package WP-Product-Merchant-Promotions-Feed-Manager
 * @since 2.39.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPPFM_Register_Scripts' ) ) :

	class WPPPFM_Register_Scripts {

		// Storage for the version stamp for the js files.
		private $_version_stamp;

		// Storage for the extension for the js files.
		private $_js_min;

		public function __construct() {
			$this->_version_stamp = defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : WPPPFM_PACKAGE_VERSION;
			$this->_js_min        = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';

			add_action( 'admin_enqueue_scripts', array( $this, 'wpppfm_register_merchant_promotions_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'wpppfm_register_merchant_promotions_nonce' ) );
		}

		public function wpppfm_register_merchant_promotions_scripts() {

			// do not load the other scripts unless a wppfm page is on
			if ( ! wppfm_on_own_main_plugin_page() ) {
				return;
			}

			// register the chosen script and style for the feed constructor Promotion Destination selector
			wp_enqueue_script( 'select2', WPPFM_PLUGIN_URL . '/includes/libraries/select2/js/select2.min.js', array( 'jquery' ), false, true );
			wp_register_style( 'select2-style', WPPFM_PLUGIN_URL . '/includes/libraries/select2/css/select2.min.css', array(), false, 'screen' );
			wp_enqueue_style( 'select2-style' );

			// register the merchant promotions specific scripts
			wp_register_style( 'wppfm-merchant-promotions-support', WPPPFM_PACKAGE_URL . '/css/promotions-feed-form' . $this->_js_min . '.css', '', false, 'screen' );
			wp_enqueue_style( 'wppfm-merchant-promotions-support' );

			// register the simple datetimepicker script and style
			wp_enqueue_script( 'simple-datetimepicker', WPPFM_PLUGIN_URL . '/includes/libraries/jQuery-Simple-Datetimepicker/jquery.simple-dtpicker.js', array( 'jquery' ), false, true );
			wp_localize_script( 'simple-datetimepicker', 'my_script_vars', array( 'language' => get_user_locale() ) );
			wp_register_style( 'simple-datetimepicker-style', WPPFM_PLUGIN_URL . '/includes/libraries/jQuery-Simple-Datetimepicker/jquery.simple-dtpicker.css', array(), false, 'screen' );
			wp_enqueue_style( 'simple-datetimepicker-style' );

			// register the merchant promotions scripts
			wp_enqueue_script( 'wpppfm_feed-constructor-script', WPPPFM_PACKAGE_URL . '/js/wpppfm-feed-constructor' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
			wp_enqueue_script( 'wpppfm_feed-form-script', WPPPFM_PACKAGE_URL . '/js/wpppfm-feed-form' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
			wp_enqueue_script( 'wpppfm_attribute-mapping-script', WPPPFM_PACKAGE_URL . '/js/wpppfm-attribute-mapping' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
			wp_enqueue_script( 'wpppfm_feed-form-events', WPPPFM_PACKAGE_URL . '/js/wpppfm-feed-form-events' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
			wp_enqueue_script( 'wpppfm_promotions-feed-handlers-script', WPPPFM_PACKAGE_URL . '/js/wpppfm-feed-handlers' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
			wp_enqueue_script( 'wpppfm_promotion-element', WPPPFM_PACKAGE_URL . '/js/wpppfm-promotion-element' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
			wp_enqueue_script( 'wpppfm_promotions-feed-actions-script', WPPPFM_PACKAGE_URL . '/js/wpppfm-feed-actions' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
			wp_enqueue_script( 'wpppfm_feed-form-tabs', WPPPFM_PACKAGE_URL . '/js/wpppfm-feed-form-tabs' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
		}

		public function wpppfm_register_merchant_promotions_nonce() {
			wp_localize_script(
				'wpppfm_ajax-data-handling-script',
				'promotionsAjaxNonce',
				array(
					// URL to wp-admin/admin-ajax.php to process the request
					'ajaxurl'                        => admin_url( 'admin-ajax.php' ),
					// generate the nonce's
					'promotionsFeedGetMainDataNonce' => wp_create_nonce( 'myajax-promotions-feed-get-main-data-nonce' ),
				)
			);
		}
	}

	// end of WPPPFM_Register_Scripts class

endif;

$my_wpppfm_ajax_registration_class = new WPPPFM_Register_Scripts();
