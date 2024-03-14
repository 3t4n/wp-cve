<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFFN_AJAX_Controller
 * Handles All the request
 */
if ( ! class_exists( 'WFFN_AJAX_Controller' ) ) {
	class WFFN_AJAX_Controller {

		public static function init() {

			/**
			 * Backend AJAX actions
			 */
			if ( is_admin() ) {
				self::handle_admin_ajax();
			}
		}

		/**
		 * Handling admin ajax
		 */
		public static function handle_admin_ajax() {
			add_action( 'wp_ajax_wffn_activate_plugin', array( __CLASS__, 'activate_plugin' ) );
		}

		/**
		 * Ajax action to activate plugin
		 */
		public static function activate_plugin() {
			check_ajax_referer( 'wffn_activate_plugin', '_nonce' );

			$plugin_init = isset( $_POST['plugin_init'] ) ? sanitize_text_field( $_POST['plugin_init'] ) : '';
			$activate    = activate_plugin( $plugin_init, '', false, true );

			if ( is_wp_error( $activate ) ) {
				wp_send_json_error( array(
					'success' => false,
					'message' => $activate->get_error_message(),
					'init'    => $plugin_init,
				) );
			}

			wp_send_json_success( array(
				'success' => true,
				'message' => __( 'Plugin Successfully Activated', 'funnel-builder' ),
				'init'    => $plugin_init,
			) );
		}



	}

	WFFN_AJAX_Controller::init();
}
