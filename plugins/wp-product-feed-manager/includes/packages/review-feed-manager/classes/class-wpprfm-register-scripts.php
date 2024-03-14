<?php

/**
 * WPPRFM Google Product Review Feed Register Scripts.
 *
 * @package WP Product Review Feed Manager/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPRFM_Register_Scripts' ) ) :

	class WPPRFM_Register_Scripts {

		// Storage for the version stamp for the js files.
		private $_version_stamp;

		// Storage for the extension for the js files.
		private $_js_min;

		public function __construct() {
			$this->_version_stamp = defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : WPPFM_VERSION_NUM;
			$this->_js_min        = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';

			add_action( 'admin_enqueue_scripts', array( $this, 'wpprfm_register_product_review_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'wpprfm_register_product_review_nonce' ) );
		}

		public function wpprfm_register_product_review_scripts() {

			// do not load the other scripts unless a wppfm page is on
			if ( ! wppfm_on_own_main_plugin_page() ) {
				return;
			}

			// register the product review scripts
			wp_enqueue_script( 'wpprfm_event-listener-script', WPPRFM_PACKAGE_URL . '/js/wpprfm-feed-form-events' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
			wp_enqueue_script( 'wpprfm_attribute-mapping-script', WPPRFM_PACKAGE_URL . '/js/wpprfm-attribute-mapping' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
			wp_enqueue_script( 'wpprfm_feed-constructor-script', WPPRFM_PACKAGE_URL . '/js/wpprfm-feed-constructors' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
			wp_enqueue_script( 'wpprfm_feed-actions-script', WPPRFM_PACKAGE_URL . '/js/wpprfm-feed-actions' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
			wp_enqueue_script( 'wpprfm_feed-form-script', WPPRFM_PACKAGE_URL . '/js/wpprfm-feed-form' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
			wp_enqueue_script( 'wpprfm_review_feed-handlers-script', WPPRFM_PACKAGE_URL . '/js/wpprfm-feed-handlers' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
			wp_enqueue_script( 'wpprfm_ajax-data-handling-script', WPPRFM_PACKAGE_URL . '/js/wpprfm-ajax-data-handling' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
		}

		public function wpprfm_register_product_review_nonce() {
			wp_localize_script(
				'wpprfm_ajax-data-handling-script',
				'reviewAjaxNonce',
				array(
					// URL to wp-admin/admin-ajax.php to process the request
					'ajaxurl'                      => admin_url( 'admin-ajax.php' ),
					// generate the nonce's
					'reviewFeedGetAttributesNonce' => wp_create_nonce( 'myajax-review-feed-get-attributes-nonce' ),
					'reviewFeedGetMainDataNonce'   => wp_create_nonce( 'myajax-review-feed-get-main-data-nonce' ),
				)
			);
		}
	}

	// end of WPPRFM_Register_Scripts class

endif;

$my_wpprfm_ajax_registration_class = new WPPRFM_Register_Scripts();
