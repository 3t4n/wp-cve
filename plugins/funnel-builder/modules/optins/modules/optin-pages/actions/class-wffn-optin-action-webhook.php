<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * This class will Create send email after optin form submit
 * Class WFFN_Optin_Action_Webhook
 */
if ( ! class_exists( 'WFFN_Optin_Action_Webhook' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Optin_Action_Webhook extends WFFN_Optin_Action {

		private static $slug = 'op_webhook_url';
		private static $ins = null;
		public $priority = 50;

		/**
		 * WFFN_Optin_Action_Webhook constructor.
		 */
		public function __construct() {
			parent::__construct();
		}

		/**
		 * @return WFFN_Optin_Action_Webhook|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public static function get_slug() {
			return self::$slug;
		}

		/**
		 * @param $posted_data
		 * @param $fields_settings
		 * @param $optin_action_settings
		 *
		 * @return array|bool|mixed
		 */
		public function handle_action( $posted_data, $fields_settings, $optin_action_settings ) {

			if ( ! is_array( $posted_data ) || count( $posted_data ) === 0 ) {
				return $posted_data;
			}

			$post_fields = $posted_data;


			if ( isset( $optin_action_settings['op_webhook_enable'] ) && wffn_string_to_bool( $optin_action_settings['op_webhook_enable'] ) === true && ! empty( $optin_action_settings['op_webhook_url'] ) ) {

				$op_webhook_url = urldecode( $optin_action_settings['op_webhook_url'] );

				$optin_webhook_request = wp_remote_post( $op_webhook_url, array( 'body' => apply_filters( 'wffn_optin_filter_webhook_fields', $post_fields ) ) );

				if ( is_wp_error( $optin_webhook_request ) ) {
					WFFN_Core()->logger->log( "Webhook Failure: " . $optin_webhook_request->get_error_message() );

					return $posted_data; // Return false on error
				}
			}

			return $posted_data;

		}
	}

	if ( class_exists( 'WFOPP_Core' ) ) {
		WFOPP_Core()->optin_actions->register( WFFN_Optin_Action_Webhook::get_instance() );
	}
}
